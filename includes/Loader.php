<?php

class MSCEC_Loader
{
    // Массив хуков-действий
    protected $actions;

    // Массив хуков-фильтров
    protected $filters;

    public function __construct()
    {
        $this->actions = [];
        $this->filters = [];
    }

    /**
     * Добавляет новый хук-действие в массив
     *
     * @param    string               $hook             Название хука-действия WordPress
     * @param    object               $component        Экземпляр класса
     * @param    string               $callback         Функция колбэк, которая будет методом указанного класса
     * @param    int                  $priority         Необязательный. Приоритет хука
     * @param    int                  $accepted_args    Необязательный. Количество аргументов колбэк функции
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Добавляет новый хук-фильтр в массив
     *
     * @param    string               $hook             Название хука-действия WordPress
     * @param    object               $component        Экземпляр класса
     * @param    string               $callback         Функция колбэк, которая будет методом указанного класса
     * @param    int                  $priority         Необязательный. Приоритет хука
     * @param    int                  $accepted_args    Необязательный. Количество аргументов колбэк функции
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Объединяет в себе все хуки
     *
     * @since    1.0.0
     * @access   private
     * @param    array                $hooks            Массив хуков, либо действия, либо фильтры
     * @param    string               $hook             Название хука-действия WordPress
     * @param    object               $component        Экземпляр класса
     * @param    string               $callback         Функция колбэк, которая будет методом указанного класса
     * @param    int                  $priority         Приоритет хука
     * @param    int                  $accepted_args    Количество аргументов колбэк функции
     * @return   array                                  Массив всех хуков
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args)
    {
        $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args
        );

        return $hooks;
    }

    // Регистрирует хуки
    public function run()
    {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'], $hook['accepted_args']);
        }
    }
}
