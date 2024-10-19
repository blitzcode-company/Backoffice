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

        $response = $this->get(route('video.detalle', ['id' => 5]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('video');
        $videoFromResponse = $response->viewData('video');
        $this->assertEquals(5, $videoFromResponse->id);
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
    public function test_subir_video()
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
            'duracion' => 120,
        ];
        $formData['video'] = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');
        $formData['miniatura'] = UploadedFile::fake()->create('miniatura.jpg', 1024, 'image/jpeg');
        $response = $this->post(route('video.crear'), $formData);
        $response->assertStatus(Response::HTTP_FOUND);
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
    $video = Video::findOrFail(4);
    Storage::fake('s3');
    $formData = [
        'titulo' => 'Nuevo Título',
        'descripcion' => 'Nueva Descripción',
        'etiquetas' => [2, 3],
    ];
    $response = $this->put(route('video.editar', ['id' => $video->id]), $formData);
    $response->assertStatus(Response::HTTP_FOUND);
    $video = $video->fresh();
    $this->assertEquals('Nuevo Título', $video->titulo);
    $this->assertEquals('Nueva Descripción', $video->descripcion);
    $formData['video'] = UploadedFile::fake()->create('video_editado.mp4', 2048, 'video/mp4');
    $formData['miniatura'] = UploadedFile::fake()->image('miniatura_editada.jpg', 1024, 1024);
    $formData['video']->store('videos', 's3');
    $formData['miniatura']->store('miniaturas', 's3');
    $response = $this->put(route('video.editar', ['id' => $video->id]), $formData);
    $response->assertStatus(Response::HTTP_FOUND);
    $video = $video->fresh();
    Storage::disk('s3')->assertExists('videos/' . $formData['video']->hashName());
    Storage::disk('s3')->assertExists('miniaturas/' . $formData['miniatura']->hashName());
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

    /** @test */
    public function mostrar_etiquetas_con_conteo_videos()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('video.etiquetas'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('etiquetas');
        $etiquetasFromResponse = $response->viewData('etiquetas');
        $this->assertNotEmpty($etiquetasFromResponse, 'Las etiquetas no se encontraron en la respuesta.');

        foreach ($etiquetasFromResponse as $etiqueta) {
            $this->assertArrayHasKey('videos_count', $etiqueta->getAttributes());
        }
    }

/** @test */
    public function listar_videos_por_etiqueta()
    {
        $user = User::first();
        $this->actingAs($user);

        $etiquetaId = 4;
        $response = $this->get(route('video.etiqueta', ['id' => $etiquetaId]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('videos');
        $response->assertViewHas('etiqueta');

        $videosFromResponse = $response->viewData('videos');
        $this->assertNotEmpty($videosFromResponse, 'Los videos no se encontraron en la respuesta.');

        foreach ($videosFromResponse as $video) {
            $this->assertTrue($video->etiquetas->contains($etiquetaId), 'El video no pertenece a la etiqueta esperada.');
        }
    }

/** @test */
    public function listar_videos_por_canal()
    {
        $user = User::first();
        $this->actingAs($user);
        $canalId = 2;
        $response = $this->get(route('video.canal', ['id' => $canalId]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewHas('videos');
        $response->assertViewHas('canalId', $canalId);
        $videosFromResponse = $response->viewData('videos');
        $this->assertNotEmpty($videosFromResponse, 'No se encontraron videos en la respuesta.');
        foreach ($videosFromResponse as $video) {
            $this->assertEquals('Canal de Diego', $video->canal->nombre, 'El video no pertenece al canal esperado.');
        }
    }

}
