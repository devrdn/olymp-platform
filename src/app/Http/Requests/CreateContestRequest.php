<?php

namespace App\Http\Requests;

use App\DTOs\CreateContestDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateContestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:150|string',
            'description' => 'string|max:1000',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ];
    }

    public function getDTO(): CreateContestDTO
    {
        return new CreateContestDTO(
            $this->get('title'),
            $this->get('description'),
            $this->get('start_time'),
            $this->get('end_time'),
        );
    }
}
