<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Playlist;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Video;
use Tests\TestCase;

class PlaylistControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function mostrar_formulario_crear_playlist()
    {
        $user = User::first();
        $this->actingAs($user);
        $response = $this->get(route('playlists.crear.formulario'));
        $response->assertStatus(200);
        $response->assertViewIs('playlists.create-playlist');
    }

    /** @test */
    public function crear_playlist()
    {

        $user = User::first();
        $this->actingAs($user);

        $user = User::find(2);
        $video1 = Video::find(7);
        $video2 = Video::find(8);
        $video3 = Video::find(9);
        $data = [
            'nombre' => 'Mi nueva Playlist',
            'acceso' => true,
            'user_id' => $user->id,
            'videos' => '7,8,9',
        ];
        $response = $this->post(route('playlists.crear'), $data);

        $response->assertRedirect(route('playlists.crear.formulario'));
        $response->assertSessionHas('success', 'Playlist creada exitosamente');
        $this->assertDatabaseHas('playlists', [
            'nombre' => 'Mi nueva Playlist',
            'acceso' => true,
            'user_id' => $user->id,
        ]);
        $playlist = Playlist::where('nombre', 'Mi nueva Playlist')->first();
        $this->assertCount(3, $playlist->videos);
        $this->assertTrue($playlist->videos->contains($video1));
        $this->assertTrue($playlist->videos->contains($video2));
        $this->assertTrue($playlist->videos->contains($video3));
    }

    /** @test */
    public function listar_playlists_por_nombre()
    {
        $user = User::first();
        $this->actingAs($user);
        $playlist = Playlist::where('nombre', 'Mi nueva Playlist')->first();
        $response = $this->get(route('playlists.listar', ['nombre' => 'Mi nueva Playlist']));
        $response->assertStatus(200);
        $response->assertViewHas('playlists');
        $playlists = $response->viewData('playlists');
        $this->assertTrue($playlists->contains(function ($p) use ($playlist) {
            return $p['nombre'] === $playlist->nombre;
        }));
    }

    /** @test */
    public function listar_playlists_por_user_id()
    {
        $user = User::first();
        $this->actingAs($user);
        $user = User::find(2);
        $playlist = Playlist::where('nombre', 'Mi nueva Playlist')->first();
        $response = $this->get(route('playlists.usuario.listar', ['id' => 2]));
        $response->assertStatus(200);
        $response->assertViewHas('playlists');
        $playlists = $response->viewData('playlists');
        $this->assertTrue($playlists->contains(function ($p) use ($playlist, $user) {
            return $p['nombre'] === $playlist->nombre && $p['propietario'] === $user->name;
        }));
    }

    /** @test */
    public function buscar_videos_por_titulo_excluyendo_invitado()
    {
        $user = User::first();
        $this->actingAs($user);
        $query = 'Título del video 1 para Canal de Sophia';
        $response = $this->get(route('playlists.buscar', ['q' => $query]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'titulo', 'descripcion', 'canal_id'],
        ]);
        $videos = $response->json();

        foreach ($videos as $video) {
            $this->assertStringContainsString($query, $video['titulo']);
            $videoModel = Video::find($video['id']);
            $this->assertNotEquals('Invitado', $videoModel->canal->user->name);
        }
    }

    /** @test */
    public function mostrar_videos_de_playlist()
    {
        $user = User::first();
        $this->actingAs($user);
        $playlist = Playlist::findOrFail(1);
        $videos = $playlist->videos;
        $this->assertGreaterThan(0, $videos->count(), 'La playlist debería tener videos asociados');
        $response = $this->get(route('playlists.videos', ['id' => $playlist->id]));
        $response->assertStatus(200);
        $response->assertViewIs('playlists.playlist-videos');
        $response->assertViewHas('playlistData', function ($playlistData) use ($playlist) {
            return $playlistData['id'] === $playlist->id &&
            $playlistData['nombre'] === $playlist->nombre &&
            $playlistData['acceso'] === $playlist->acceso &&
            $playlistData['propietario'] === $playlist->user->name &&
            $playlistData['user_id'] === $playlist->user->id;
        });
        $response->assertViewHas('videos', function ($viewVideos) use ($videos) {
            return $viewVideos->contains(function ($video) use ($videos) {
                return $videos->contains($video);
            });
        });
    }

    /** @test */
    public function cambiar_acceso_de_playlist()
    {
        $user = User::first();
        $this->actingAs($user);
        $playlist = Playlist::first();
        $originalAccess = $playlist->acceso;
        $data = ['acceso' => !$originalAccess];
        $response = $this->post(route('playlists.acceso', ['id' => $playlist->id]), $data);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $playlist->refresh();
        $this->assertNotEquals($originalAccess, $playlist->acceso, 'El acceso de la playlist no se ha cambiado correctamente');
    }

    /** @test */
    public function editar_nombre_de_playlist()
    {
        $user = User::first();
        $this->actingAs($user);
        $playlist = Playlist::first();
        $nuevoNombre = 'Nuevo Nombre de Playlist';
        $response = $this->put(route('playlists.editar', ['id' => $playlist->id]), [
            'nombre' => $nuevoNombre,
        ]);
        $response->assertRedirect(route('playlists.videos', ['id' => $playlist->id]));
        $response->assertSessionHas('success', 'El nombre de la playlist ha sido actualizado correctamente.');
        $playlist->refresh();
        $this->assertEquals($nuevoNombre, $playlist->nombre, 'El nombre de la playlist no se ha actualizado correctamente');
    }

/** @test */
    public function borrar_playlist()
    {
        $user = User::first();
        $this->actingAs($user);
        $playlist = Playlist::first();
        $response = $this->delete(route('playlists.eliminar', ['id' => $playlist->id]));
        $response->assertRedirect(route('playlists.listar'));
        $response->assertSessionHas('success', 'La playlist ha sido eliminada correctamente.');
        $this->assertSoftDeleted('playlists', ['id' => $playlist->id]);
    }
}
