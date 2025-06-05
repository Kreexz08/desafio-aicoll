<?php

namespace Tests\Unit\Application\Empresa\Create;

use Tests\TestCase;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Application\Empresa\Create\CreateEmpresaCommand;
use App\Application\Empresa\Create\CreateEmpresaCommandHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\Exceptions\DuplicateNitException;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use App\Domain\Empresa\ValueObjects\Nit;
use App\Application\Command; // Necesario para el type hint en handle()
use InvalidArgumentException; // Necesario para el type hint en handle()
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\Test; // ¡LA LÍNEA IMPORTANTE QUE FALTABA!

class CreateEmpresaCommandHandlerTest extends TestCase
{
    private EmpresaRepositoryInterface|MockObject $empresaRepositoryMock;
    private CreateEmpresaCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresaRepositoryMock = $this->createMock(EmpresaRepositoryInterface::class);
        $this->handler = new CreateEmpresaCommandHandler($this->empresaRepositoryMock);
    }

    #[Test]
    public function handle_deberia_guardar_y_devolver_una_empresa_exitosa(): void
    {
        // 1. ARRANGE
        $command = new CreateEmpresaCommand(
            nit: '900888999-0',
            nombre: 'Empresa de Prueba Unitaria',
            direccion: 'Calle de la Prueba 123',
            telefono: '3009998877'
        );

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('existsByNit')
            ->willReturn(false);

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->willReturn(Empresa::fromArray($command->toArray() + ['id' => 1, 'created_at' => now(), 'updated_at' => now()]));

        // 2. ACT
        $result = $this->handler->handle($command);

        // 3. ASSERT
        $this->assertInstanceOf(Empresa::class, $result);
        $this->assertEquals('Empresa de Prueba Unitaria', $result->getNombre());
    }

    #[Test]
    public function handle_deberia_lanzar_excepcion_si_el_nit_ya_existe(): void
    {
        // 1. ARRANGE
        $command = new CreateEmpresaCommand(
            nit: '900888999-0',
            nombre: 'Empresa Duplicada',
            direccion: 'Dirección Duplicada',
            telefono: '3001112233'
        );

        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('existsByNit')
            ->willReturn(true);

        $this->expectException(DuplicateNitException::class);
        $this->expectExceptionMessage("Ya existe una empresa con el NIT: {$command->nit}");

        $this->empresaRepositoryMock
            ->expects($this->never())
            ->method('save');

        // 2. ACT
        $this->handler->handle($command);
    }

    // He añadido el test para el argumento inválido del handler
    #[Test]
    public function handle_deberia_lanzar_excepcion_si_el_comando_es_incorrecto(): void
    {
        // ARRANGE
        // Creamos un comando anónimo que implementa la interfaz base
        $invalidCommand = new class implements Command {
            public function toArray(): array { return []; }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Se esperaba un comando de tipo CreateEmpresaCommand.');

        // ACT
        $this->handler->handle($invalidCommand);
    }
}
