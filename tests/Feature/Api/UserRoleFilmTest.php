<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRoleFilmTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function only_an_user_with_role_films_can_query_films_endpoint()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '0' => [
                'id',
                'title',
                'original_title',
                'original_title_romanised',
                'image',
                'movie_banner',
                'description',
                'director',
                'producer',
                'release_date',
                'running_time',
                'rt_score',
                'people',
                'species',
                'locations',
                'vehicles',
                'url',
            ]
        ]);
    }

    #[Test]
    public function an_user_with_different_role_cannot_query_films_endpoint()
    {
        $user = User::factory()->vehicles()->create();

        $response = $this->actingAs($user)->get("/api/v1/films");

        $response->assertStatus(403);

        $response->assertExactJson([
            "status" => "error",
            "message" => "No tienes permiso para consultar peliculas.",
            "errors" => [
                "authorization" => "Acceso denegado"
            ],
            "code" => 403
        ]);
    }

    #[Test]
    public function can_append_field_in_films_endpoint_for_search()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films?id=2baf70d1-42bb-4437-b551-e5fed5a87abe");

        $response->assertStatus(200);
    }

    #[Test]
    public function can_append_two_or_more_field_in_films_endpoint_for_search()
    {
        $user = User::factory()->films()->create();

        $response = $this->actingAs($user)->get("/api/v1/films?id=2baf70d1-42bb-4437-b551-e5fed5a87abe,producer=Isao+Takahata,release_date=1986");

        $response->assertStatus(200);

        $response->assertExactJson([
            [
                'id' => '2baf70d1-42bb-4437-b551-e5fed5a87abe',
                'title' => 'Castle in the Sky',
                'original_title' => '天空の城ラピュタ',
                'original_title_romanised' => 'Tenkū no shiro Rapyuta',
                'image' => 'https://image.tmdb.org/t/p/w600_and_h900_bestv2/npOnzAbLh6VOIu3naU5QaEcTepo.jpg',
                'movie_banner' => 'https://image.tmdb.org/t/p/w533_and_h300_bestv2/3cyjYtLWCBE1uvWINHFsFnE8LUK.jpg',
                'description' => "The orphan Sheeta inherited a mysterious crystal that links her to the mythical sky-kingdom of Laputa. With the help of resourceful Pazu and a rollicking band of sky pirates, she makes her way to the ruins of the once-great civilization. Sheeta and Pazu must outwit the evil Muska, who plans to use Laputa's science to make himself ruler of the world.",
                'director' => 'Hayao Miyazaki',
                'producer' => 'Isao Takahata',
                'release_date' => '1986',
                'running_time' => '124',
                'rt_score' => '95',
                'people' => [ "https://ghibliapi.vercel.app/people/598f7048-74ff-41e0-92ef-87dc1ad980a9",
                    "https://ghibliapi.vercel.app/people/fe93adf2-2f3a-4ec4-9f68-5422f1b87c01",
                    "https://ghibliapi.vercel.app/people/3bc0b41e-3569-4d20-ae73-2da329bf0786",
                    "https://ghibliapi.vercel.app/people/40c005ce-3725-4f15-8409-3e1b1b14b583",
                    "https://ghibliapi.vercel.app/people/5c83c12a-62d5-4e92-8672-33ac76ae1fa0",
                    "https://ghibliapi.vercel.app/people/e08880d0-6938-44f3-b179-81947e7873fc",
                    "https://ghibliapi.vercel.app/people/2a1dad70-802a-459d-8cc2-4ebd8821248b"],
                'species' => ['https://ghibliapi.vercel.app/species/af3910a6-429f-4c74-9ad5-dfe1c4aa04f2'],
                'locations' => ['https://ghibliapi.vercel.app/locations/'],
                'vehicles' => ['https://ghibliapi.vercel.app/vehicles/4e09b023-f650-4747-9ab9-eacf14540cfb'],
                'url' => 'https://ghibliapi.vercel.app/films/2baf70d1-42bb-4437-b551-e5fed5a87abe',
            ]
        ]);
    }
}
