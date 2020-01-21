<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasPosition
{
    /**
     * @return void
     */
    protected static function bootHasPosition()
    {
        static::creating(function (Model $model) {
            $model->generatePositionOnCreate();
        });

        static::updating(function (Model $model) {
            $model->generatePositionOnUpdate();
        });

        static::deleting(function (Model $model) {
            $model->generatePositionOnDelete();
        });
    }

    /**
     * @return void
     */
    protected function generatePositionOnCreate()
    {
        $this->setAttribute($this->getPositionColumn(), $this->getPositionGroupCount() + 1);
    }

    /**
     * @return void
     */
    protected function generatePositionOnUpdate()
    {

    }

    /**
     * @return void
     */
    protected function generatePositionOnDelete()
    {
        $positionColumn = $this->getPositionColumn();
        $this->query()
            ->where($this->createPositionGroupCondition())
            ->where($positionColumn, '>', $this->getAttribute($positionColumn))
            ->decrement($positionColumn);
    }

    /**
     * @return $this
     */
    public function movePrev()
    {
        $positionColumn = $this->getPositionColumn();
        $this->query()
            ->where($this->createPositionGroupCondition())
            ->where($positionColumn, ($this->getAttribute($positionColumn) - 1))
            ->update([$positionColumn => $this->getAttribute($positionColumn)]);
        $this->update([$positionColumn => $this->getAttribute($positionColumn) - 1]);

        return $this;
    }

    /**
     * @return $this
     */
    public function moveNext()
    {
        $positionColumn = $this->getPositionColumn();
        $this->query()
            ->where($this->createPositionGroupCondition())
            ->where($positionColumn, ($this->getAttribute($positionColumn) + 1))
            ->update([$positionColumn => $this->getAttribute($positionColumn)]);
        $this->update([$positionColumn => $this->getAttribute($positionColumn) + 1]);

        return $this;
    }

    /**
     * @return $this
     */
    public function moveFirst()
    {
        $positionColumn = $this->getPositionColumn();

        if ($this->getAttribute($positionColumn) != 1) {
            $this->query()
                ->where($this->createPositionGroupCondition())
                ->where($positionColumn, '<', $this->getAttribute($positionColumn))
                ->increment($positionColumn);
            $this->update([$positionColumn => 1]);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function moveLast()
    {
        $count = $this->getPositionGroupCount();
        $positionColumn = $this->getPositionColumn();

        if ($this->getAttribute($positionColumn) != $count) {
            $this->query()
                ->where($this->createPositionGroupCondition())
                ->where($positionColumn, '>', $this->getAttribute($positionColumn))
                ->decrement($positionColumn);
            $this->update([$positionColumn => $count]);
        }

        return $this;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function moveToPosition(int $position)
    {
        if ($position < 1) {
            return $this;
        }

        $positionColumn = $this->getPositionColumn();

        if ($this->getAttribute($positionColumn) == $position) {
            return $this;
        }

        if ($position < $this->getAttribute($positionColumn)) {
            $this->query()
                ->where($this->createPositionGroupCondition())
                ->where($positionColumn, '>=', $position)
                ->where($positionColumn, '<', $this->getAttribute($positionColumn))
                ->increment($positionColumn);
            $this->update([$positionColumn => $position]);

            return $this;
        }

        $count = $this->getPositionGroupCount();

        if ($position >= $count) {
            return $this->moveLast();
        }

        $this->query()
            ->where($this->createPositionGroupCondition())
            ->where($positionColumn, '>', $this->getAttribute($positionColumn))
            ->where($positionColumn, '<=', $position)
            ->decrement($positionColumn);
        $this->update([$positionColumn => $position]);

        return $this;
    }

    /**
     * @return string
     */
    public function getPositionColumn()
    {
        return 'position';
    }

    /**
     * @return string|array
     */
    public function getPositionGroupColumn()
    {
        return [];
    }

    /**
     * @return integer
     */
    protected function getPositionGroupCount()
    {
        return $this->query()
            ->where($this->createPositionGroupCondition())
            ->count();
    }

    /**
     * @return array
     */
    protected function createPositionGroupCondition()
    {
        $condition = [];

        if (!empty($this->getPositionGroupColumn())) {
            foreach ((array) $this->getPositionGroupColumn() as $attribute) {
                $condition[$attribute] = $this->$attribute;
            }
        }

        return $condition;
    }
}
