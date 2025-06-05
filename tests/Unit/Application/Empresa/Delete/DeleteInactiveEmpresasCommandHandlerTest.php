<?php

namespace Tests\Unit\Application\Empresa\Delete;

use Tests\TestCase;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Application\Empresa\Delete\DeleteInactiveEmpresasCommand;
use App\Application\Empresa\Delete\DeleteInactiveEmpresasCommandHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\Test;

class DeleteInactiveEmpresasCommandHandlerTest extends TestCase
{
    private EmpresaRepositoryInterface|MockObject $empresaRepositoryMock;
    private DeleteInactiveEmpresasCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresaRepositoryMock = $this->createMock(EmpresaRepositoryInterface::class);
        $this->handler = new DeleteInactiveEmpresasCommandHandler($this->empresaRepositoryMock);
    }

    #[Test]
    public function handle_deberia_llamar_al_metodo_deleteInactives_y_devolver_el_conteo(): void
    {
        // 1. ARRANGE
        $command = new DeleteInactiveEmpresasCommand();
        $expectedCount = 5; // Un número de ejemplo de empresas eliminadas

        // Configuramos el mock para que cuando se llame a 'deleteInactives', devuelva nuestro número de ejemplo.
        $this->empresaRepositoryMock
            ->expects($this->once()) // Esperamos que se llame una vez
            ->method('deleteInactives')
            ->willReturn($expectedCount);

        // 2. ACT
        $result = $this->handler->handle($command);

        // 3. ASSERT
        // Verificamos que el resultado del handler sea el mismo número que devolvió nuestro mock.
        $this->assertEquals($expectedCount, $result);
    }
}
