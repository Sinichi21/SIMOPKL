<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        About::create([
            'content' => '<div style="display:flex;gap:1rem;">
    <div style="flex:1;">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis ipsa tenetur hic ea fugiat provident, quibusdam necessitatibus esse inventore nemo! Mollitia, debitis dolorem soluta molestiae hic reprehenderit. Harum voluptatum eum iste quo, error aperiam voluptates ratione molestiae quam recusandae iure adipisci odio quos consectetur et numquam dolorum! Laborum tenetur aperiam, natus necessitatibus possimus soluta dolore, fugit, nisi dignissimos corporis hic non ut et. Voluptatum in molestiae veritatis, maxime adipisci rem eligendi, vel dicta saepe explicabo, iusto maiores minus dolorum sed!
    </div>
    <div style="flex:1;">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero molestias commodi consectetur error laudantium perferendis in. Natus sint, eum adipisci veritatis, consectetur, voluptatibus doloribus nisi cumque culpa minus a et possimus. Atque saepe laborum minima. Autem quis iure incidunt exercitationem nam perferendis eum maxime impedit quisquam dolores! Tenetur, maiores doloremque iusto consequuntur corporis atque nostrum animi non assumenda architecto? Iure voluptatibus, ab neque, quas facilis id minus corrupti doloribus dicta numquam accusamus rem, porro dolores voluptatum vitae assumenda adipisci quasi esse soluta voluptatem. Dolore similique inventore optio quas veniam illo.
    </div>
</div>',
        ]);
    }
}
