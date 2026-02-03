<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'cpf' => $this->generateCpf(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address_id' => Address::factory(),
        ];
    }

    private function generateCpf(): string
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[$i] = rand(0, 9);
        }

        // Primeiro dígito verificador
        $d1 = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $d1 += $n[$i] * $j;
        }
        $d1 = $d1 % 11;
        $d1 = ($d1 < 2) ? 0 : 11 - $d1;
        $n[9] = $d1;

        // Segundo dígito verificador
        $d2 = 0;
        for ($i = 0, $j = 11; $i < 10; $i++, $j--) {
            $d2 += $n[$i] * $j;
        }
        $d2 = $d2 % 11;
        $d2 = ($d2 < 2) ? 0 : 11 - $d2;
        $n[10] = $d2;

        return sprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', ...$n);
    }
}
