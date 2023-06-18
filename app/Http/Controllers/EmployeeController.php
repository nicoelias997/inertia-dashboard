<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::join('departments', 'departments.id', '=', 'employees.department_id')
            ->select('employees.id', 'employees.name', 'email', 'phone', 'department_id', 'departments.name as department')
            ->get();
            
        //seleccionamos los empleados mediante sus id, para no confundir los departamentos, y los unimos a la tabla de departments

        $departments = Department::all(); //llamamos a toda la tabla de departments
        return Inertia::render(
            'Employees/Index',
            [
                'employees' => $employees,
                'departments' => $departments
            ]
        ); //renderizo el componente a employes index, y envio como parametro los empleados y departamentos
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|max:80',
            'phone' => 'required|max:15',
            'department_id' => 'required|numeric'
        ]);

        $employee = new Employee($request->input());
        $employee->save();
        return redirect('employees');
    }


    public function show(Employee $employee)
    {
        //
    }

    public function edit(Employee $employee)
    {
        //
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|max:80',
            'phone' => 'required|max:15',
            'department_id' => 'required|numeric'
        ]);
        $employee->update($request->input());
        return redirect('employees');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect('employees');
    }

    public function EmployeeByDepartment()
    {
        $data = DB::table('employees') //selecciona de la base de dato y cuenta, lo renombramos como count, nombre de los departamentos
            ->select(DB::raw('count(employees.id) as count, departments.name'))
            ->join('departments', "departments.id", '=', 'employees.department_id')
            ->groupByRaw('departments.name') //lo unimos a la tabla de departments y lo organizamos por nombre de departamentos. 
            ->get();
        return Inertia::render('Employees/Graphic', ['data' => $data]); //Inertia renderiza la data, hacia una vista, y enviamos la informacion mediante[props]
    }
    public function reports()
    {
        $employees = Employee::select('employees.id', 'employees.name', 'email', 'phone', 'department_id', 'departments.name as department')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->get(); //seleccionamos los empleados mediante sus id, para no confundir los departamentos, y los unimos a la tabla de departments y lo traemos

        $departments = Department::all(); //llamamos a toda la tabla de departments
        return Inertia::render(
            'Employees/Reports',
            [
                'employees' => $employees,
                'departments' => $departments
            ]
        ); //renderizo el componente a employes Reports, y envio como parametro los empleados y departamentos
    }
}
