@extends('layouts::app')

@section('title', 'Create Contest')

@section('subtitle', 'Contest')

@section('description')
    {{ __('Create a new contest by filling out the form below.') }}
@endsection

@section('content')
    <x-forms.wrapper>
        <x-forms.fieldset legend="Details">
            <x-forms.input-widget for="title" :title="__('Title')" required />
            <x-forms.textarea-widget rows="6" style="resize: none;" for="description" :title="__('Description')" required />
        </x-forms.fieldset>
        <x-forms.fieldset legend="Dates">
            <x-forms.block>
                <x-forms.input-widget type="datetime-local" for="start_date" :title="__('Start Date')" required />
                <x-forms.input-widget type="datetime-local" for="end_date" :title="__('End Date')" required />
            </x-forms.block>
        </x-forms.fieldset>
        <div>
            <x-primary-button>{{ __('Create') }}</x-primary-button>
        </div>
    </x-forms.wrapper>
@endsection
