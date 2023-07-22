<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = array(
            ['title' =>"The origin of names", 'slug' => "nameHistory", 'content' => '
            # The Origin of Names

Names have always been a significant aspect of human society, serving as a way to identify individuals and groups. From ancient times, naming people has been a celebrated affair, with cultures developing unique naming traditions that reflect their beliefs, customs, and traditions.

## The Early Days

It is believed that naming started in the early days of human civilization when groups of people would gather and identify themselves through a common name. They would use these names to identify each other, trade, hunt, and fight together. These early names were often based on physical attributes or geographical features.

For instance, the name "Hill" came from people who lived on or near hills, "Strong" for those who were physically strong, and "Smith" for those who worked in a blacksmith profession. As human civilization evolved and developed languages, names became more sophisticated, with increasing use of symbolism, mythology, and religion.

## Naming Traditions

Different cultures have developed unique naming traditions that reflect their customs and beliefs. In some cultures, the name is given based on the time of birth, position in the family, or other cultural factors. In other cultures, the name is given based on the religion or mythology of the group.

For instance, In Chinese culture, a person is name is based on their birthplace, date of birth, and time. In some African cultures, people are named after famous ancestors or events that occurred during their birth. In Indian culture, a person is name is based on their horoscope, with an astrologer giving the parents the recommended name.

## Changing Times

Over time, naming traditions have continued to evolve, with modern parents choosing names based on personal preference or trends. Today, people are given names based on popular culture, celebrities, or even products. Parents are also increasingly creative in naming their children, often combining names or using unique spellings.

However, despite the changing traditions, names remain an integral part of human society, connecting us across generations and cultures. Every name has a unique origin, history, and significance that adds to the diversity and richness of human experience.

In conclusion, the history and evolution of naming are an intriguing aspect of human civilization. From simple physical attributes to complex religious and cultural symbolism, names have played a significant role in shaping our collective identity. As we continue to evolve, naming traditions will undoubtedly continue to change, reflecting our changing values, customs, and beliefs.

', 'user_id' => '0', 'language' => 'en'],

        );
      
        Article::insert($data);
    }
}
