<?php

namespace Tests\Feature;

use App\Models\Blitzvideo\Canal;
use App\Models\Blitzvideo\Stream;
use App\Models\Blitzvideo\User;
use App\Models\Blitzvideo\Video;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StreamControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'blitzvideo']);
        if (!Schema::hasColumn('streams', 'canal_id')) {
            Schema::table('streams', function (Blueprint $table) {
                $table->foreignId('canal_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }

    /** @test */
    public function mostrar_formulario_subida_stream()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get(route('stream.crear.formulario'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('stream.subir-stream');
    }

    /** @test */
    public function crear_stream()
    {
        $user = User::first();
        $this->actingAs($user);
        Storage::fake('s3');

        $canal = Canal::first();
        $file = UploadedFile::fake()->image('miniatura.jpg');

        $data = [
            'canal_id' => $canal->id,
            'titulo' => 'Nuevo Stream de Prueba',
            'descripcion' => 'Descripción del stream de prueba',
            'miniatura' => $file,
        ];

        $response = $this->post(route('stream.crear'), $data);

        $response->assertRedirect(route('stream.crear.formulario'));
        $response->assertSessionHas('success', 'Stream creado exitosamente.');
        $this->assertDatabaseHas('videos', [
            'titulo' => 'Nuevo Stream de Prueba',
            'descripcion' => 'Descripción del stream de prueba',
            'estado' => 'PROGRAMADO',
            'canal_id' => $canal->id,
        ]);

        $video = Video::where('titulo', 'Nuevo Stream de Prueba')->first();
        $this->assertDatabaseHas('streams', [
            'video_id' => $video->id,
            'activo' => 0,
        ]);

        Storage::disk('s3')->assertExists('miniaturas-streams/' . $canal->id . '/' . $file->hashName());
    }

    /** @test */
    public function listar_streams()
    {
        $user = User::first();
        $this->actingAs($user);

        $this->crearStreamDePrueba();

        $response = $this->get(route('stream.streams'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('stream.streams');
        $response->assertViewHas('streams');
    }

    /** @test */
    public function listar_streams_por_nombre()
    {
        $user = User::first();
        $this->actingAs($user);

        $stream = $this->crearStreamDePrueba('Stream Unico Para Buscar');

        $response = $this->post(route('stream.nombre'), ['nombre' => 'Stream Unico']);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('stream.streams');
        $response->assertViewHas('streams', function ($streams) use ($stream) {
            return $streams->contains('id', $stream->id);
        });
    }

    /** @test */
    public function mostrar_stream()
    {
        $user = User::first();
        $this->actingAs($user);

        $stream = $this->crearStreamDePrueba();

        $response = $this->get(route('stream.detalle', ['id' => $stream->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('stream.stream');
        $response->assertViewHas('stream');
        $response->assertViewHas('link');
    }

    /** @test */
    public function mostrar_formulario_editar_stream()
    {
        $user = User::first();
        $this->actingAs($user);

        $stream = $this->crearStreamDePrueba();

        $response = $this->get(route('stream.editar.formulario', ['id' => $stream->id]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('stream.editar-stream');
        $response->assertViewHas('stream');
    }

    /** @test */
    public function editar_stream()
    {
        $user = User::first();
        $this->actingAs($user);
        Storage::fake('s3');

        $stream = $this->crearStreamDePrueba();
        $video = Video::find($stream->video_id);
        $newFile = UploadedFile::fake()->image('nueva_miniatura.jpg');

        $data = [
            'titulo' => 'Titulo Editado',
            'descripcion' => 'Descripcion Editada',
            'miniatura' => $newFile,
        ];

        $response = $this->put(route('stream.editar', ['id' => $stream->id]), $data);

        $response->assertRedirect(route('stream.editar.formulario', ['id' => $stream->id]));
        $response->assertSessionHas('success', 'Stream editado exitosamente.');

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'titulo' => 'Titulo Editado',
            'descripcion' => 'Descripcion Editada',
        ]);

        Storage::disk('s3')->assertExists('miniaturas-streams/' . $video->canal_id . '/' . $newFile->hashName());
    }

    /** @test */
    public function eliminar_stream()
    {
        $user = User::first();
        $this->actingAs($user);
        Storage::fake('s3');

        $stream = $this->crearStreamDePrueba();
        $videoId = $stream->video_id;

        $response = $this->delete(route('stream.eliminar', ['id' => $stream->id]));

        $response->assertRedirect(route('stream.streams'));
        $response->assertSessionHas('success', 'Stream eliminado exitosamente.');

        $this->assertSoftDeleted('videos', ['id' => $videoId]);
    }

    private function crearStreamDePrueba($titulo = 'Stream de Prueba')
    {
        $canal = Canal::first();
        if (!$canal) {
            $user = User::first();
            $canal = Canal::create([
                'nombre' => 'Canal Test',
                'user_id' => $user->id,
                'stream_key' => 'key_test'
            ]);
        }

        $video = Video::create([
            'canal_id' => $canal->id,
            'titulo' => $titulo,
            'descripcion' => 'Descripcion de prueba',
            'link' => 'stream_' . uniqid(),
            'miniatura' => 'path/to/miniatura.jpg',
            'estado' => 'PROGRAMADO',
            'acceso' => 'publico',
        ]);

        $stream = new Stream();
        $stream->video_id = $video->id;
        $stream->activo = false;
        $stream->canal_id = $canal->id;
        $stream->save();

        return $stream;
    }
}