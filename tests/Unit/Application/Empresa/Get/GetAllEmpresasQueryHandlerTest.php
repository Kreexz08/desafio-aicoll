<?php

namespace Tests\Unit\Application\Empresa\Get;

use Tests\TestCase;
use App\Domain\Empresa\Interfaces\EmpresaRepositoryInterface;
use App\Application\Empresa\Get\GetAllEmpresasQuery;
use App\Application\Empresa\Get\GetAllEmpresasQueryHandler;
use App\Domain\Empresa\Entities\Empresa;
use App\Domain\Empresa\ValueObjects\Estado;
use App\Domain\Empresa\Exceptions\InvalidEmpresaDataException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\Attributes\Test;

class GetAllEmpresasQueryHandlerTest extends TestCase
{
    private EmpresaRepositoryInterface|MockObject $empresaRepositoryMock;
    private GetAllEmpresasQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->empresaRepositoryMock = $this->createMock(EmpresaRepositoryInterface::class);
        $this->handler = new GetAllEmpresasQueryHandler($this->empresaRepositoryMock);
    }

    #[Test]
    public function handle_deberia_llamar_a_findAll_cuando_no_hay_paginacion(): void
    {
        // 1. ARRANGE
        // Creamos una query sin parámetros de paginación.
        $query = new GetAllEmpresasQuery();

        // Simulamos que el repositorio devuelve un array vacío de empresas.
        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]); // Devolvemos un array vacío para el ejemplo

        // 2. ACT
        $result = $this->handler->handle($query);

        // 3. ASSERT
        $this->assertIsArray($result);
    }

    #[Test]
    public function handle_deberia_llamar_a_findAllPaginated_cuando_hay_paginacion(): void
    {
        // 1. ARRANGE
        // Creamos una query CON parámetros de paginación.
        $query = new GetAllEmpresasQuery(estado: null, page: 1, perPage: 10);

        // Creamos un mock del paginador de Laravel.
        $paginatorMock = $this->createMock(LengthAwarePaginator::class);

        // Simulamos que el repositorio devuelve el mock del paginador.
        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findAllPaginated')
            ->with(null, 1, 10) // Verificamos que se llame con los argumentos correctos
            ->willReturn($paginatorMock);

        // 2. ACT
        $result = $this->handler->handle($query);

        // 3. ASSERT
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    #[Test]
    public function handle_deberia_pasar_un_estado_vo_al_repositorio_cuando_se_filtra(): void
    {
        // 1. ARRANGE
        $query = new GetAllEmpresasQuery(estado: 'Activo');

        // Configuramos el mock para verificar que el argumento 'estado' que recibe
        // sea un objeto Estado con el valor 'Activo'.
        $this->empresaRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->with($this->callback(function ($estado) {
                return $estado instanceof Estado && $estado->getValue() === 'Activo';
            }))
            ->willReturn([]);

        // 2. ACT
        $this->handler->handle($query);
    }

    #[Test]
    public function handle_deberia_lanzar_excepcion_si_el_filtro_de_estado_es_invalido(): void
    {
        // 1. ARRANGE
        $query = new GetAllEmpresasQuery(estado: 'Pendiente');

        // Esperamos la excepción de validación del VO Estado.
        $this->expectException(InvalidEmpresaDataException::class);

        // 2. ACT
        $this->handler->handle($query);
    }
}
