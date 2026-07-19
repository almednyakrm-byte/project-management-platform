<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\مشاريع;
use App\Repositories\مشاريعRepository;
use Mockery;
use Mockery\MockInterface;

class Testمشاريع extends TestCase
{
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = Mockery::mock(\PDO::class);
        $this->repository = new مشاريعRepository($this->pdo);
    }

    public function testGetAll()
    {
        $expectedData = [
            ['id' => 1, 'name' => 'Project 1'],
            ['id' => 2, 'name' => 'Project 2'],
        ];

        $this->pdo->shouldReceive('query')
            ->with('SELECT * FROM مشاريع')
            ->andReturn(Mockery::mock(\PDOStatement::class))
            ->once();

        $stmt = Mockery::mock(\PDOStatement::class);
        $stmt->shouldReceive('fetchAll')
            ->andReturn($expectedData)
            ->once();

        $this->pdo->shouldReceive('query')
            ->andReturn($stmt);

        $data = $this->repository->getAll();

        $this->assertEquals($expectedData, $data);
    }

    public function testCreate()
    {
        $data = [
            'name' => 'New Project',
        ];

        $expectedId = 3;

        $this->pdo->shouldReceive('exec')
            ->with('INSERT INTO مشاريع (name) VALUES (:name)', ['name' => $data['name']])
            ->andReturn($expectedId)
            ->once();

        $id = $this->repository->create($data);

        $this->assertEquals($expectedId, $id);
    }

    public function testUpdate()
    {
        $data = [
            'id' => 1,
            'name' => 'Updated Project',
        ];

        $this->pdo->shouldReceive('exec')
            ->with('UPDATE مشاريع SET name = :name WHERE id = :id', ['name' => $data['name'], 'id' => $data['id']])
            ->andReturn(1)
            ->once();

        $this->repository->update($data);
    }

    public function testDelete()
    {
        $id = 1;

        $this->pdo->shouldReceive('exec')
            ->with('DELETE FROM مشاريع WHERE id = :id', ['id' => $id])
            ->andReturn(1)
            ->once();

        $this->repository->delete($id);
    }
}



// App\Models\مشاريع.php

namespace App\Models;

class مشاريع
{
    public $id;
    public $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}



// App\Repositories\مشاريعRepository.php

namespace App\Repositories;

use App\Models\مشاريع;
use PDO;

class مشاريعRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->query('SELECT * FROM مشاريع');
        return $stmt->fetchAll();
    }

    public function create(array $data)
    {
        $stmt = $this->pdo->exec('INSERT INTO مشاريع (name) VALUES (:name)', $data);
        return $this->pdo->lastInsertId();
    }

    public function update(array $data)
    {
        $stmt = $this->pdo->exec('UPDATE مشاريع SET name = :name WHERE id = :id', $data);
        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->pdo->exec('DELETE FROM مشاريع WHERE id = :id', ['id' => $id]);
        return $stmt;
    }
}