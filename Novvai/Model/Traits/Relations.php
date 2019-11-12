<?php
namespace Novvai\Model\Traits;

use Novvai\Container;

trait Relations
{

    /**
     * @param string $className : App\Models\User
     * @param string $identifier : id
     * @param string $on : parent_id
     * 
     * @return Stackable
     */
    public function hasMany(string $className, string $identifier = null, string $on = null)
    {
        $identifier = $identifier ?: 'id';
        $child = Container::make($className);
        $relation_name = get_short_name($this);

        $on = $on ?: $relation_name . '_id';

        return $this->{debug_backtrace()[1]['function']} = $child->where($on, $this->{$identifier})->all();
    }
    /**
     * @param string $className : App\Models\User
     * @param string $identifier : id
     * @param string $on : child_id
     * 
     * @return Stackable
     */
    public function belongsTo(string $className, string $identifier = null, string $on = null)
    {

        $identifier = $identifier ?: 'id';
        $parent = Container::make($className);
        $relation_name = get_short_name($parent);

        $on = $on ?: $relation_name . '_id';

        return $this->{debug_backtrace()[1]['function']} = $parent->where($identifier, $this->{$on})->get()->first();
    }
}
