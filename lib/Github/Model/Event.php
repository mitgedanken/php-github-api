<?php

namespace Github\Model;

use Github\Exception;

class Event extends AbstractModel
{
    protected static $_properties = array(
        'repo',
        'id',
        'url',
        'actor',
        'event',
        'commit_id',
        'created_at',
        'issue'
    );

    public static function factory(Repo $repo, $event, $issue = null)
    {
        return new Event($repo, $event, $issue);
    }

    public static function fromArray(Repo $repo, array $data, Issue $issue = null)
    {
        $event = Event::factory($repo, $data['event'], $issue);

        if (isset($data['actor'])) {
            $data['actor'] = User::fromArray($data['actor']);
        }

        if (isset($data['issue'])) {
            $data['issue'] = Issue::fromArray($repo, $data['issue']);
        }

        return $event->hydrate($data);
    }

    public function __construct($repo, $event, $issue = null)
    {
        $this->repo = $repo;
        $this->event = $event;

        if ($issue) {
            $this->issue = $issue;
        }
    }

    public function show()
    {
        $data = $this->api('issue')->events()->show(
            $this->repo->owner->login,
            $this->repo->name,
            $this->event
        );

        return Event::fromArray($this->repo, $data);
    }
}
