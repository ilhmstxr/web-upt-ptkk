<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class DynamicTableWidget extends BaseWidget
{
    // Properti untuk menerima data dari luar
    public string $model;
    public array $columnDefinitions = [];
    public array $actionDefinitions = [];
    public ?Closure $query = null;

    // DIUBAH: Nama properti diganti untuk menghindari konflik dengan properti '$heading' dari parent class.
    public ?string $widgetHeading = null;

    public ?string $description = null;

    // Sembunyikan header default statis
    protected static ?string $heading = '';

    public function getTableHeading(): ?string
    {
        // DIUBAH: Menggunakan properti baru yang sudah diganti namanya.
        return $this->widgetHeading;
    }

    public function getTableDescription(): ?string
    {
        return $this->description;
    }

    public function table(Table $table): Table
    {
        // Bangun kolom secara dinamis
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

            if (isset($definition['searchable'])) {
                $column->searchable($definition['searchable']);
            }
            if (isset($definition['sortable'])) {
                $column->sortable($definition['sortable']);
            }

            $columns[] = $column;
        }

        // Bangun Aksi secara dinamis
        $actions = [];
        foreach ($this->actionDefinitions as $definition) {
            $action = Tables\Actions\Action::make($definition['name'])
                ->label($definition['label'] ?? '')
                ->icon($definition['icon'])
                ->color($definition['color'] ?? 'primary')
                ->url(fn($record): string => ($definition['url'])($record))
                ->openUrlInNewTab(isset($definition['newTab']));

            $actions[] = $action;
        }

        return $table
            ->query(function () {
                $baseQuery = ($this->query) ? call_user_func($this->query) : $this->getModel()::query();
                return $baseQuery;
            })
            ->columns($columns)
            ->actions($actions);
    }
}

