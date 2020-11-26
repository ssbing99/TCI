<?php

use Illuminate\Database\Seeder;

class ConfigUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contact_data = array(
            0 =>
                array(
                    'name' => 'short_text',
                    'value' => 'Welcome to our Website. We are glad to have you around.',
                    'status' => 1,
                ),
            1 =>
                array(
                    'name' => 'primary_address',
                    'value' => 'Surburban Site - 3928 Dickens Dr. Plano, TX 75023 \nFarm Site - Ben Franklin, TX',
                    'status' => 1,
                ),
            2 =>
                array(
                    'name' => 'secondary_address',
                    'value' => 'Surburban Site - 3928 Dickens Dr. Plano, TX 75023 \nFarm Site - Ben Franklin, TX',
                    'status' => 1,
                ),
            3 =>
                array(
                    'name' => 'primary_phone',
                    'value' => '(1) 214 856 8477',
                    'status' => 1,
                ),
            4 =>
                array(
                    'name' => 'secondary_phone',
                    'value' => '(1) 214 856 8477',
                    'status' => 1,
                ),
            5 =>
                array(
                    'name' => 'primary_email',
                    'value' => 'learn@schoolofpermaculture.com',
                    'status' => 1,
                ),
            6 =>
                array(
                    'name' => 'secondary_email',
                    'value' => 'learn@schoolofpermaculture.com',
                    'status' => 1,
                ),
            7 =>
                array(
                    'name' => 'location_on_map',
                    'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3344.275801757153!2d-96.70378628547405!3d33.04920507736546!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c19b3dc02ccf3%3A0xdce1e7c649ea28d1!2s3928%20Dickens%20Dr%2C%20Plano%2C%20TX%2075023%2C%20USA!5e0!3m2!1sen!2sin!4v1603719892272!5m2!1sen!2sin" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>',
                    'status' => 1,
                ),
        );

        $contact_data = json_encode($contact_data);
        $data = [
            'contact_data' => $contact_data,
        ];

        foreach ($data as $key => $value) {
            $key = str_replace('__', '.', $key);
            $config = \App\Models\Config::firstOrCreate(['key' => $key]);
            $config->value = $value;
            $config->save();
        }
    }
}
