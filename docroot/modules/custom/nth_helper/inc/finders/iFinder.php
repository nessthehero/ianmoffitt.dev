<?php

  namespace Nth\Finders;

  interface iFinder
  {

    public function __construct($taxonomy, $machine, $fields);
    public function setFields($fields);
    public function reset();
    public function results($amt, $offset);
    public function remove($items);
    public function shuffle();
    public function filterBy($column, $value, $matcher);
    public function setSort($sort);
    public function count($filtered);
    public function pages($amt);
    public function first();
    public function last();
    public function get();
    public function random();

  }
