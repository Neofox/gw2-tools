<?php
/**
 * Created by PhpStorm.
 * User: Neofox
 * Date: 25/04/2016
 * Time: 10:50
 */

namespace AppBundle\Services\Topic;

use AppBundle\Services\WvWService;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicPeriodicTimerInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicPeriodicTimerTrait;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;

class WvWTopic implements TopicInterface, TopicPeriodicTimerInterface
{
    use TopicPeriodicTimerTrait;

    /**
     * @var WvWService
     */
    private $wvwService;

    /**
     * WvWTopic constructor.
     *
     * @param WvWService $wvwService
     */
    public function __construct(WvWService $wvwService)
    {
        $this->wvwService = $wvwService;
    }


    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param WampRequest         $request
     *
     * @return void
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast(['msg' => $connection->resourceId . " has joined " . $topic->getId()]);
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param WampRequest         $request
     *
     * @return void
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast(['msg' => $connection->resourceId . " has left " . $topic->getId()]);
    }


    /**
     * This will receive any Publish requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param WampRequest         $request
     * @param                     $event
     * @param array               $exclude
     * @param array               $eligible
     *
     * @return mixed|void
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
            if ($topic->getId() === 'wvw/channel'){

                $topic->broadcast([
                    'msg' => $event,
                ]);
            }
    }

    /**
     * Like RPC is will use to prefix the channel
     * @return string
     */
    public function getName()
    {
        return 'wvw.topic';
    }

    /**
     * @param Topic $topic
     *
     * @return mixed
     */
    public function registerPeriodicTimer(Topic $topic)
    {
        try {

            $this->wvwService->setMapObjectives($this->wvwService->getObjectives('2-6'));
            //add
            $this->periodicTimer->addPeriodicTimer($this, 'wvwConsole', 10, function () use ($topic) {
                $takenObjectives = $this->wvwService->stringifyTakenObjectives('2-6');

                if (!empty($takenObjectives)) {
                    foreach ($takenObjectives as $takenObjective) {
                        $topic->broadcast($takenObjective);
                    }
                }
            });
        }catch (\Exception $e){
            echo "bim!";
            throw new $e;
        }
    }

}