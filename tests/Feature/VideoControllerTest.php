<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Video;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
    }

    /** @test */
    public function mostrar_todos_los_videos()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('video.listar'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('videos');
    }

    /** @test */
    public function listar_videos_por_nombre()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->post(route('video.nombre'), ['nombre' => 'Título del video 1 para Canal de Diego']);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('videos');
    }

    /** @test */
    public function mostrar_informacion_video()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('video.detalle', ['id' => 4]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('video');
        $videoFromResponse = $response->viewData('video');
        $this->assertEquals(4, $videoFromResponse->id);
        $this->assertTrue($videoFromResponse->relationLoaded('canal'));
        $this->assertTrue($videoFromResponse->relationLoaded('etiquetas'));
    }

    /** @test */
    public function mostrar_formulario_subida()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('video.crear.formulario'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('etiquetas');
        $etiquetasFromResponse = $response->viewData('etiquetas');
        $this->assertNotEmpty($etiquetasFromResponse, 'Las etiquetas no se encontraron en la respuesta.');
    }

    /** @test */
    public function mostrar_formulario_editar()
    {
        $user = User::first();
        $this->actingAs($user);

        $idVideo = 4;
        $response = $this->get(route('video.editar.formulario', ['id' => $idVideo]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('etiquetas');
        $response->assertViewHas('video');
        $etiquetasFromResponse = $response->viewData('etiquetas');
        $this->assertNotEmpty($etiquetasFromResponse, 'Las etiquetas no se encontraron en la respuesta.');
        $videoFromResponse = $response->viewData('video');
        $this->assertEquals($idVideo, $videoFromResponse->id, 'El video no coincide con el ID esperado.');
    }

    /** @test */
    public function subir_video()
    {
        $user = User::first();
        $this->actingAs($user);

        Storage::fake('s3');
        $canalId = 3;
        $formData = [
            'titulo' => 'Título del Video',
            'descripcion' => 'Descripción del Video',
            'canal_id' => $canalId,
            'etiquetas' => [4, 5],
        ];
        $formData['video'] = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');
        $formData['miniatura'] = UploadedFile::fake()->create('miniatura.jpg', 1024, 'image/jpeg');
        $response = $this->post(route('video.crear'), $formData);
        $response->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('videos', [
            'titulo' => 'Título del Video',
            'descripcion' => 'Descripción del Video',
            'canal_id' => $canalId,
        ]);
        Storage::disk('s3')->assertExists('videos/' . $canalId . '/' . $formData['video']->hashName());
        Storage::disk('s3')->assertExists('miniaturas/' . $canalId . '/' . $formData['miniatura']->hashName());
        $response->assertRedirect(route('video.crear.formulario'));
        $response->assertSessionHas('success', 'Video subido exitosamente');
    }

    /** @test */
    public function editar_video()
    {
        $user = User::first();
        $this->actingAs($user);

        Storage::fake('s3');
        $video = Video::findOrFail(4);
        $formData = [
            'titulo' => 'Nuevo Título',
            'descripcion' => 'Nueva Descripción',
            'etiquetas' => [2, 3],
        ];
        $formData['video'] = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');
        $formData['miniatura'] = UploadedFile::fake()->create('miniatura.jpg', 1024, 'image/jpeg');
        $response = $this->put(route('video.editar', ['id' => $video->id]), $formData);
        $response->assertStatus(Response::HTTP_FOUND);
        $video = Video::findOrFail($video->id);
        $this->assertEquals('Nuevo Título', $video->titulo);
        $this->assertEquals('Nueva Descripción', $video->descripcion);
        Storage::disk('s3')->assertExists('videos/' . $video->canal_id . '/' . $formData['video']->hashName());
        Storage::disk('s3')->assertExists('miniaturas/' . $video->canal_id . '/' . $formData['miniatura']->hashName());
        $response->assertRedirect(route('video.editar.formulario', ['id' => $video->id]));
        $response->assertSessionHas('success', 'Video editado exitosamente');
    }

    /** @test */
    public function baja_video()
    {
        $user = User::first();
        $this->actingAs($user);

        $canalId = 3;
        $video = Video::where('canal_id', $canalId)->latest()->first();
        $this->assertNotNull($video);
        $response = $this->delete(route('video.eliminar', ['id' => $video->id]));
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('video.listar'));
        $response->assertSessionHas('success', 'Video dado de baja correctamente');
        $this->assertSoftDeleted('videos', [
            'id' => $video->id,
        ]);
    }
}
