<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class DynamicTableWidget extends BaseWidget
{
    // ===================================================================
    // PROPERTI BARU YANG "LIVEWIRE-FRIENDLY"
    // ===================================================================
    public string $model;
    public array $columnDefinitions = [];
    public array $actionDefinitions = [];
    public ?string $widgetHeading = null;
    public ?string $description = null;

    // Properti untuk membangun query
    public ?array $with = []; // Untuk eager loading
    public ?string $orderByColumn = null;
    public string $orderByDirection = 'desc';
    public ?int $limit = null;
    // ===================================================================

    protected static ?string $heading = '';

    public function getTableHeading(): ?string
    {
        return $this->widgetHeading;
    }

    public function getTableDescription(): ?string
    {
        return $this->description;
    }

    protected function getTableQuery(): Builder
    {
        // ===================================================================
        // LOGIKA PEMBANGUNAN QUERY DIPINDAHKAN KE SINI
        // ===================================================================
        $query = $this->model::query();

        if (!empty($this->with)) {
            $query->with($this->with);
        }

        if ($this->orderByColumn) {
            $query->orderBy($this->orderByColumn, $this->orderByDirection);
        }

        if ($this->limit) {
            $query->limit($this->limit);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        // Bangun kolom secara dinamis (logika ini tetap sama)
        $columns = [];
        foreach ($this->columnDefinitions as $name => $definition) {
            $type = $definition['type'] ?? 'text';
            $label = $definition['label'] ?? ucfirst($name);

            $column = null;
            switch ($type) {
                case 'badge':
                    $column = Tables\Columns\BadgeColumn::make($name)
                        ->colors($definition['colors'] ?? []);
                    break;
                case 'icon':
                    $column = Tables\Columns\IconColumn::make($name)
                        ->boolean();
                    break;
                default:
                    $column = Tables\Columns\TextColumn::make($name);
                    break;
            }

            $column->label($label);
            if (isset($definition['searchable'])) $column->searchable($definition['searchable']);
            if (isset($definition['sortable'])) $column->sortable($definition['sortable']);
            $columns[] = $column;
        }

        // Bangun Aksi secara dinamis (logika ini tetap sama)
        $actions = [];
        foreach ($this->actionDefinitions as $definition) {
            $urlCallback = $definition['url'] ?? null;
            if (!$urlCallback || !is_callable($urlCallback)) continue;

            $action = Tables\Actions\Action::make($definition['name'])
                ->label($definition['label'] ?? '')
                ->icon($definition['icon'])
                ->color($definition['color'] ?? 'primary')
                ->url(fn($record): string => $urlCallback($record))
                ->openUrlInNewTab(isset($definition['newTab']));
            $actions[] = $action;
        }

        // DIUBAH: Menggunakan metode getTableQuery() yang baru
        return $table
            ->query($this->getTableQuery())
            ->columns($columns)
            ->actions($actions);
    }
}

