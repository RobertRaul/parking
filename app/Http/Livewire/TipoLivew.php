<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Tipo;
use Livewire\WithPagination;

class TipoLivew extends Component
{
    //paginado
    use WithPagination;
    //Tipo de paginacion
    protected $paginationTheme = 'bootstrap';
    //acciones
    public $Campo = 'tip_id';
    public $OrderBy = 'desc';
    public $pagination = 5;
    public $buscar = '';
    //propiedades
    public $tip_desc, $tip_img;
    // Id y Actualizar
    public $selected_id = null, $selected_id_edit = null;
    public $updateMode = false;

    public function render()
    {
        $data=Tipo::query()
        ->search($this->buscar)
        ->orderBy($this->Campo,$this->OrderBy)
        ->paginate($this->pagination);

        return view('livewire.tipos.listado',[
            'data' => $data
        ]);
    }

    protected $rules =
    [
        'tip_desc'  => 'required|unique:tipo,tip_desc',

        'tip_img'   =>  'image',
    ];

    protected $messages =
    [
        'tip_desc.required'  => 'El campo es requerido',
        'tip_desc.unique'    => 'Ya existe un registro con ese valor',

        'tip_img.image'      => 'Solo se aceptan imagenes'
    ];
    //validaciones en vivo
    public function updated($propertyName)
    {
        //dentro de este mnetodo se pone todas la validacione en vivo
        $this->validateOnly($propertyName, [
            'tip_desc'  => 'required|unique:tipo,tip_desc,' . $this->selected_id_edit . ',tip_id',

            'tip_img'   =>  'image',
        ]);
    }

    public function updatingSearch(): void
    {
        $this->resetPage(1);
    }
    public function Header_Orderby($campo_a_ordenar)
    {
        if ($this->OrderBy == 'asc')
            $this->OrderBy = 'desc';
        else
            $this->OrderBy = 'asc';

        return $this->Campo = $campo_a_ordenar;
    }
    //limpiar los inputs
    public function resetInput()
    {
        $this->tip_desc = null;
        $this->tip_img = null;
        $this->tip_estado = null;

        $this->selected_id = null;
        $this->selected_id_edit = null;

        $this->buscar = '';

        $this->updateMode = false;
    }
    //cancelar y limpiar imputs
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }

    //metodo registrar y actualizar
    public function store_update()
    {
        $datos =
            [
                'tip_desc'   => $this->tip_desc,
                'tip_img'     => $this->tip_img,
            ];
        //realizamos validacion para registrar
        if ($this->selected_id_edit <= 0) {
            $this->validate();
            Tipo::create($datos);
            $this->emit('closeModal');
            $this->emit('msgOK', 'Registro Creado');
        } else //realizamos la actualizacion
        {
            $this->validate([
                'tip_desc'  => 'required|unique:tipo,tip_desc,' . $this->selected_id_edit . ',tip_id',
                'tip_img'   =>  'image',
            ]);
            Tipo::find($this->selected_id_edit)->update($datos);
            $this->emit('closeModal');
            $this->emit('msgEDIT', 'Registro Modificado');
        }
        $this->resetInput();
    }
    //metodo para
    public function edit($id)
    {
        $data = Tipo::findOrFail($id);

        $this->selected_id_edit = $id;
        $this->tip_desc = $data->tip_desc;
        $this->tip_img = $data->tip_img;

        $this->updateMode = true;
    }
    //Pone el valor del ID a la propieda $selected_id
    public function Confirmar_Desactivar($id)
    {
        $this->selected_id = $id;
    }

    //Desactiva y activa dependiente del valor enviado
    public function Desactivar_Activar($id, $value)
    {
        $record = Tipo::find($id);
        $record->update([
            'tip_estado' => $value
        ]);
        $this->emit('msgINFO', 'Registro ' . $value);
        $this->resetInput();
    }
    //Elimina los mensajes de error luego de las validaciones
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
