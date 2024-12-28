<?php 
namespace App\Traits;

trait ModelEnabled{

    /**
     * Checks if the model is enabled
     *
     * @return bool
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function isEnabled(): bool
    {
        return $this->enabled == 1;
    }

    /**
     * Enables the model
     *
     * @return bool If enabled
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function enable(): bool
    {
        $this->enabled = 1;
        $this->save();
        return $this->save() && $this->isEnabled();
    }

    /**
     * Disables the model
     *
     * @return bool If enabled
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function disable(): bool
    {
        $this->enabled = 0;
        return $this->save() && !$this->isEnabled();
    }
}
