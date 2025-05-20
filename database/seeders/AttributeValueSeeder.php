<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
    public function run()
    {
        $attributes = Attribute::all()->keyBy('name');

        $attributeValues = [
            'Kích Thước' => ['100cm', '120cm', '150cm', '180cm'],
            'Ngôn Ngữ' => ['Tiếng Việt', 'Tiếng Anh', 'Tiếng Pháp', 'Tiếng Nhật'],
            'Loại Bìa' => ['Bìa Mềm', 'Bìa Cứng', 'Bìa Bồi'],
        ];

        foreach ($attributeValues as $attrName => $values) {
            $attribute = $attributes->get($attrName);
            if (!$attribute) {
                $this->command->warn("Attribute $attrName not found");
                continue;
            }

            foreach ($values as $value) {
                AttributeValue::updateOrCreate(
                    ['attribute_id' => $attribute->id, 'value' => $value],
                    []
                );
            }
        }
    }
}
