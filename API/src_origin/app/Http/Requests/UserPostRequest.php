<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
// use App\Models\Adress;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UserPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Ignored for now as authentification isn't fixed
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
            'pseudoname' => 'required|alpha_num:ascii|max:50|unique:users',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email:rfc,strict,filter_unicode|unique:users',
            'password' => 'required',
            'role' => ['required', 'alpha_num:ascii', Rule::in(User::getRoleNames())],
            'registered_adress' => 'nullable|array',
            'registered_adress.*' => 'exists:adresses,id',
        ];
    }

    public function after(): array
    {

        return [
            /*
            //adress existance validation
            function(Validator $V) {
                $adress = $this->get('registered_adress');

                if ($adress === null) return;

                checkIfAdressIdListExist($V, $adress);
            },
*/
        ];
    }
}

function checkIfAdressIdListExist(Validator $V, array $adressIdList): void
{
    foreach ($adressIdList as $adressId) {
        if (adressIdDoesNotExists($adressId)) {
            addError($V, 'registered_adress', "$adressId does not exists");
        }
    }
}
function addError(Validator $validator, string $fieldName, string $errorMessage): void
{
    $validator->errors()->add($fieldName, $errorMessage);
}
function adressIdExists(string $id): bool
{
    return Adress::where('id', '=', $id)->exists();
}
function adressIdDoesNotExists(string $id): bool
{
    return ! adressIdExists($id);
}
