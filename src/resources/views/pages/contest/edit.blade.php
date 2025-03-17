@extends('layouts::admin')

@section('subtitle', __('Contest'))

@section('title', __('Edit Contest'))

@section('description')
    {{ __('Edit a contest by filling out the form below.') }}
@endsection

@section('content')
    <x-forms.wrapper action="{{ route('contest.update', $contest->id) }}" method="post">
        @csrf
        @method('put')
        <x-forms.input-widget name="id" :title="__('ID')" value="{{ $contest->id }}" disabled />
        <x-forms.fieldset legend="Details">
            <x-forms.input-widget name="title" :title="__('Title')" value="{{ $contest->title }}" required />
            <x-forms.textarea-widget name="description" rows="6" style="resize: none;"
                :title="__('Description')">{{ $contest->description }}</x-forms.textarea-widget>
        </x-forms.fieldset>
        <x-forms.fieldset legend="Dates">
            <x-forms.block>
                <x-forms.input-widget type="datetime-local" name="start_time" :title="__('Start Date')"
                    value="{{ $contest->start_time }}" required />
                <x-forms.input-widget type="datetime-local" name="end_time" :title="__('End Date')"
                    value="{{ $contest->end_time }}" required />
            </x-forms.block>
        </x-forms.fieldset>
        <div>
            <x-primary-button>{{ __('Update') }}</x-primary-button>
        </div>
    </x-forms.wrapper>
@endsection
