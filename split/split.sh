#!/usr/bin/env bash

git subsplit publish "
    Bridge/DoctrineDBALBridge:git@github.com:dlakomski/DoctrineDBALBridge.git
    Bridge/DoctrineORMBridge:git@github.com:dlakomski/DoctrineORMBridge.git
    Bridge/JMSSerializerBridge:git@github.com:dlakomski/JMSSerializerBridge.git

    Bundle/AsynchronousBundle:git@github.com:dlakomski/AsynchronousBundle.git
    Bundle/JMSSerializerBundleBridge:git@github.com:dlakomski/JMSSerializerBundleBridge.git
    Bundle/RabbitMQBundleBridge:git@github.com:dlakomski/RabbitMQBundleBridge.git
    Bundle/SymfonyBridge:git@github.com:dlakomski/SymfonyBridge.git

    Component/Asynchronous:git@github.com:dlakomski/Asynchronous.git
    Component/MessageBus:git@github.com:dlakomski/MessageBus.git
    Component/Serialization:git@github.com:dlakomski/Serialization.git
" --update --heads="master gh-pages"
