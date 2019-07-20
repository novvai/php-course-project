<?php

namespace Novvai\QueryBuilders\Interfaces;

interface QueryBuilderInterface
{
    /** DB queries */
    public function getQuery(): string;
    public function buildQuery(): QueryBuilderInterface;
    public function where(...$args): QueryBuilderInterface;
    public function orWhere(...$args): QueryBuilderInterface;
    public function andWhere(...$args): QueryBuilderInterface;

    /**
     * DB creation
     * [collumn fields]
     */
    public function text(): QueryBuilderInterface;
    public function unique(): QueryBuilderInterface;
    public function notNull(): QueryBuilderInterface;
    public function addTimeStamps(): QueryBuilderInterface;
    public function addSoftDelete(): QueryBuilderInterface;
    public function string(int $max): QueryBuilderInterface;
    public function integer(int $max): QueryBuilderInterface;
    public function float($max, $points): QueryBuilderInterface;
    public function default($defaultValue): QueryBuilderInterface;
    public function decimal($max, $points): QueryBuilderInterface;
    public function autoIncrement(string $name = "id"): QueryBuilderInterface;

    /** 
     * DB managment
     */
    public function finishMigration(): QueryBuilderInterface;
    public function drop(string $tableName): QueryBuilderInterface;
    public function addCollumn(string $collumn): QueryBuilderInterface;
    public function startMigration(string $tableName): QueryBuilderInterface;
}
