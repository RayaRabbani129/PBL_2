<?php

namespace App\Filament\FieldAdmin\Widgets;

use App\Models\Field;
use Filament\Widgets\Widget;

class FieldStatusWidget extends Widget
{
    protected string $view = 'filament.field-admin.widgets.field-status-widget';

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = '15s';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $venueIds = auth()->user()
            ->managedVenues()
            ->pluck('venues.id');

        $fields = Field::whereIn('venue_id', $venueIds)
            ->with('venue')
            ->latest()
            ->get();

        return compact('fields');
    }

    public function toggleAvailable(int $fieldId): void
    {
        $field = Field::findOrFail($fieldId);
        $field->update(['is_available' => ! $field->is_available]);
    }

    public function toggleStatus(int $fieldId): void
    {
        $field = Field::findOrFail($fieldId);
        $field->update([
            'status' => $field->status === 'active' ? 'inactive' : 'active',
        ]);
    }
}