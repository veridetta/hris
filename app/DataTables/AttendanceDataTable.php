<?php

namespace App\DataTables;

use App\Models\Attendance;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AttendanceDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Attendance $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $data = Attendance::select('employees.id AS id','employees.name as employees.name','schedules.dates as dates','shifts.in as in','shifts.out as out','attendances.at_in as at_in','attendances.at_out as at_out','attendances.lembur as lembur')->join('employees', 'employees.id', '=', 'attendances.employees_id')->join('schedules', 'schedules.id', '=', 'attendances.schedules_id')->join('shifts','shifts.id','=','schedules.shifts_id')->where('status','!=','Belum Masuk') ;
        return $this->applyScopes($data);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('attendance-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            
            Column::make('id')
              ->title('Id')
            ->searchable(false),
            Column::make('employees.name')
              ->title('Nama')
              ->searchable(true),
            Column::make('dates')
              ->title('Tanggal')
        ->searchable(false),
            Column::make('at_in')
              ->title('Absen Masuk')
            ->searchable(false),
            Column::make('at_out')  
            ->title('Absen Keluar')
        ->searchable(false),
            Column::make('lembur')
              ->title('Lembur')
        ->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
}
