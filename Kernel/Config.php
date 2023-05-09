<?php


namespace Kernel;


use Throwable;

class Config
{
    const STRING = "string";
    const INTEGER = "integer";

    private array $config = [];
    private bool $failed = false;

    /**
     * Config constructor.
     */
    public function __construct(string $settingsFile)
    {
        // Try parse file without warnings
        $try = @parse_ini_file($settingsFile);

        if (!$try) {
            $this->failed = true;
        } else {
            $this->config = $try;
        }
    }

    public function isFailed(): bool
    {
        return $this->failed;
    }

    /**
     * Check whether the config file is corrupted or has some missing parameters
     * @return bool
     */
    public function checkForRequiredProperties(): bool
    {
        if (
            $this->isFailed()
        )
            return false;

        $check = [
              "database_host" => "string",
              "database_port" => "string",
              "database_name" => "string",
              "database_user" => "string",
              "database_password" => "string",
        ];

        foreach ($check as $param => $type) {
            if(gettype($this->get($param)) != $type)
                return false;
        }

        return true;
    }

    /**
     * Get specific parameter from config
     * @param string $key
     * @return string|int|null
     */
    public function get(string $key): string|int|null
    {
        return $this->config[$key] ?? null;
    }
}