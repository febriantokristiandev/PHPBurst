<?php

namespace App\Handler\SessionHandlers;

use Illuminate\Database\Capsule\Manager as Capsule;
use SessionHandlerInterface;
use Carbon\Carbon;

class DatabaseSessionHandler implements SessionHandlerInterface
{
    protected $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function open(string $savePath, string $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string
    {
        $session = Capsule::table($this->table)->where('id', $id)->first();
        return $session ? $session->data : '';
    }

    public function write(string $id, string $data): bool
    {
        $exists = Capsule::table($this->table)->where('id', $id)->exists();
        if ($exists) {
            return Capsule::table($this->table)
                ->where('id', $id)
                ->update(['data' => $data, 'updated_at' => Carbon::now()]) > 0;
        } else {
            return Capsule::table($this->table)->insert([
                'id' => $id,
                'data' => $data,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]) > 0;
        }
    }

    public function destroy(string $id): bool
    {
        return Capsule::table($this->table)->where('id', $id)->delete() > 0;
    }

    public function gc(int $maxlifetime): int|false
    {
        $expiration = Carbon::now()->subSeconds($maxlifetime);
        return Capsule::table($this->table)
            ->where('updated_at', '<', $expiration)
            ->delete();
    }
}
