<?php

namespace App\Livewire\DynamicForm;

use App\Models\FundedItem;
use App\Models\School;
use Livewire\Component;

class FundedItemForm extends Component
{
    public $id;
    public $school;
    public $confirmingFundedItemDeletion = false;

    public function mount($id)
    {
        $this->school = School::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.dynamic-form.funded-item-form');
    }

    public function confirmFundedItemDeletion($id)
    {
        $this->confirmingFundedItemDeletion = $id;
    }

    public function deleteFundedItem()
    {
        try {
            $funded_item = FundedItem::find($this->confirmingFundedItemDeletion);

            if ($funded_item) {
                $funded_item->delete();
                session()->flash('flash', ['banner' => 'Appointment Funding data deleted successfully.', 'bannerStyle' => 'success']);
            }
        } catch (\Exception $e) {
            session()->flash('flash', ['banner' => 'Failed to delete Appointment Funding data.', 'bannerStyle' => 'danger']);
        }
        $this->confirmingFundedItemDeletion = false;
        return redirect()->back();
    }
}
