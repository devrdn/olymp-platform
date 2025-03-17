@extends('layouts::app')

@section('title', 'Create Contest')

@section('subtitle', 'Contest')

@section('description')
    {{ __('Create a new contest by filling out the form below.') }}
@endsection

@section('content')
    <x-forms.wrapper action="{{ route('contest.store') }}" method="post">
        @csrf
        <x-forms.fieldset legend="Details">
            <x-forms.input-widget name="title" :title="__('Title')" value="{{ old('name') }}" required />
            <x-forms.textarea-widget name="description" rows="6" style="resize: none;"
                :title="__('Description')">{{ old('description') }}</x-forms.textarea-widget>
        </x-forms.fieldset>
        <x-forms.fieldset legend="Dates">
            <x-forms.block>
                <x-forms.input-widget type="datetime-local" name="start_time" :title="__('Start Date')"
                    value="{{ old('start_time') }}" required />
                <x-forms.input-widget type="datetime-local" name="end_time" :title="__('End Date')" value="{{ old('end_time') }}"
                    required />
            </x-forms.block>
        </x-forms.fieldset>
        <div>
            <x-primary-button>{{ __('Create') }}</x-primary-button>
        </div>
    </x-forms.wrapper>
@endsection
