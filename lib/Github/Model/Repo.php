<?php

namespace Github\Model;

class Repo extends AbstractModel
{
    public $owner;
    public $name;

    public function __construct(Owner $owner, $name)
    {
        $this->owner    = $owner;
        $this->name     = $name;
    }

    public function show()
    {
        return $this->api('repo')->show($this->owner->name, $this->name);
    }

    public function createIssue($title, array $params)
    {
        $params['title'] = $title;

        $issue = $this->api('issue')->create(
            $this->owner->name,
            $this->name,
            $params
        );

        return $this->getIssue($issue['number']);
    }

    public function issue($number)
    {
        return new Issue($this->owner, $this, $number);
    }

    public function labels()
    {
        return $this->api('repo')->labels()->all(
            $this->owner->name,
            $this->name
        );
    }

    public function label($name)
    {
        return $this->api('repo')->labels()->show(
            $this->owner->name,
            $this->name,
            $name
        );
    }

    // Todo: check label doesnt exist
    public function addLabel($name, $color)
    {
        return $this->api('repo')->labels()->create(
            $this->owner->name,
            $this->name,
            array(
                'name' => $name,
                'color' => $color
            )
       );
    }

    public function updateLabel($name, $color)
    {
        $label = new Label($this, $name);

        return $label->update($name, $color);
    }

    public function removeLabel($name)
    {
        $label = new Label($this, $name);

        return $label->remove($name);
    }
}