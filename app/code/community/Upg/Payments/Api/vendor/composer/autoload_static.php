<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita6a8066d8501c06ca5ddb4b01cd61a77
{
    public static $prefixLengthsPsr4 = array (
        'U' => 
        array (
            'Upg\\Library\\' => 12,
        ),
        'S' => 
        array (
            'Sirius\\Validation\\' => 18,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Upg\\Library\\' => 
        array (
            0 => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src',
        ),
        'Sirius\\Validation\\' => 
        array (
            0 => __DIR__ . '/..' . '/siriusphp/validation/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
    );

    public static $classMap = array (
        'Monolog\\ErrorHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/ErrorHandler.php',
        'Monolog\\Formatter\\ChromePHPFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ChromePHPFormatter.php',
        'Monolog\\Formatter\\ElasticaFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ElasticaFormatter.php',
        'Monolog\\Formatter\\FlowdockFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FlowdockFormatter.php',
        'Monolog\\Formatter\\FluentdFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FluentdFormatter.php',
        'Monolog\\Formatter\\FormatterInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FormatterInterface.php',
        'Monolog\\Formatter\\GelfMessageFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/GelfMessageFormatter.php',
        'Monolog\\Formatter\\HtmlFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/HtmlFormatter.php',
        'Monolog\\Formatter\\JsonFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/JsonFormatter.php',
        'Monolog\\Formatter\\LineFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LineFormatter.php',
        'Monolog\\Formatter\\LogglyFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LogglyFormatter.php',
        'Monolog\\Formatter\\LogstashFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LogstashFormatter.php',
        'Monolog\\Formatter\\MongoDBFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/MongoDBFormatter.php',
        'Monolog\\Formatter\\NormalizerFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/NormalizerFormatter.php',
        'Monolog\\Formatter\\ScalarFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ScalarFormatter.php',
        'Monolog\\Formatter\\WildfireFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/WildfireFormatter.php',
        'Monolog\\Handler\\AbstractHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractHandler.php',
        'Monolog\\Handler\\AbstractProcessingHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php',
        'Monolog\\Handler\\AbstractSyslogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractSyslogHandler.php',
        'Monolog\\Handler\\AmqpHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AmqpHandler.php',
        'Monolog\\Handler\\BrowserConsoleHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/BrowserConsoleHandler.php',
        'Monolog\\Handler\\BufferHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/BufferHandler.php',
        'Monolog\\Handler\\ChromePHPHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ChromePHPHandler.php',
        'Monolog\\Handler\\CouchDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/CouchDBHandler.php',
        'Monolog\\Handler\\CubeHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/CubeHandler.php',
        'Monolog\\Handler\\Curl\\Util' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/Curl/Util.php',
        'Monolog\\Handler\\DeduplicationHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DeduplicationHandler.php',
        'Monolog\\Handler\\DoctrineCouchDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DoctrineCouchDBHandler.php',
        'Monolog\\Handler\\DynamoDbHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DynamoDbHandler.php',
        'Monolog\\Handler\\ElasticSearchHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php',
        'Monolog\\Handler\\ErrorLogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ErrorLogHandler.php',
        'Monolog\\Handler\\FilterHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FilterHandler.php',
        'Monolog\\Handler\\FingersCrossedHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossedHandler.php',
        'Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ActivationStrategyInterface.php',
        'Monolog\\Handler\\FingersCrossed\\ChannelLevelActivationStrategy' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ChannelLevelActivationStrategy.php',
        'Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ErrorLevelActivationStrategy.php',
        'Monolog\\Handler\\FirePHPHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FirePHPHandler.php',
        'Monolog\\Handler\\FleepHookHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FleepHookHandler.php',
        'Monolog\\Handler\\FlowdockHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FlowdockHandler.php',
        'Monolog\\Handler\\GelfHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/GelfHandler.php',
        'Monolog\\Handler\\GroupHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/GroupHandler.php',
        'Monolog\\Handler\\HandlerInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HandlerInterface.php',
        'Monolog\\Handler\\HandlerWrapper' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HandlerWrapper.php',
        'Monolog\\Handler\\HipChatHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HipChatHandler.php',
        'Monolog\\Handler\\IFTTTHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/IFTTTHandler.php',
        'Monolog\\Handler\\LogEntriesHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/LogEntriesHandler.php',
        'Monolog\\Handler\\LogglyHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/LogglyHandler.php',
        'Monolog\\Handler\\MailHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MailHandler.php',
        'Monolog\\Handler\\MandrillHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MandrillHandler.php',
        'Monolog\\Handler\\MissingExtensionException' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MissingExtensionException.php',
        'Monolog\\Handler\\MongoDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MongoDBHandler.php',
        'Monolog\\Handler\\NativeMailerHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NativeMailerHandler.php',
        'Monolog\\Handler\\NewRelicHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NewRelicHandler.php',
        'Monolog\\Handler\\NullHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NullHandler.php',
        'Monolog\\Handler\\PHPConsoleHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PHPConsoleHandler.php',
        'Monolog\\Handler\\PsrHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PsrHandler.php',
        'Monolog\\Handler\\PushoverHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PushoverHandler.php',
        'Monolog\\Handler\\RavenHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RavenHandler.php',
        'Monolog\\Handler\\RedisHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RedisHandler.php',
        'Monolog\\Handler\\RollbarHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RollbarHandler.php',
        'Monolog\\Handler\\RotatingFileHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RotatingFileHandler.php',
        'Monolog\\Handler\\SamplingHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SamplingHandler.php',
        'Monolog\\Handler\\SlackHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SlackHandler.php',
        'Monolog\\Handler\\SocketHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SocketHandler.php',
        'Monolog\\Handler\\StreamHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/StreamHandler.php',
        'Monolog\\Handler\\SwiftMailerHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SwiftMailerHandler.php',
        'Monolog\\Handler\\SyslogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogHandler.php',
        'Monolog\\Handler\\SyslogUdpHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogUdpHandler.php',
        'Monolog\\Handler\\SyslogUdp\\UdpSocket' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogUdp/UdpSocket.php',
        'Monolog\\Handler\\TestHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/TestHandler.php',
        'Monolog\\Handler\\WhatFailureGroupHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/WhatFailureGroupHandler.php',
        'Monolog\\Handler\\ZendMonitorHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ZendMonitorHandler.php',
        'Monolog\\Logger' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Logger.php',
        'Monolog\\Processor\\GitProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/GitProcessor.php',
        'Monolog\\Processor\\IntrospectionProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/IntrospectionProcessor.php',
        'Monolog\\Processor\\MemoryPeakUsageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryPeakUsageProcessor.php',
        'Monolog\\Processor\\MemoryProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryProcessor.php',
        'Monolog\\Processor\\MemoryUsageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryUsageProcessor.php',
        'Monolog\\Processor\\ProcessIdProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/ProcessIdProcessor.php',
        'Monolog\\Processor\\PsrLogMessageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/PsrLogMessageProcessor.php',
        'Monolog\\Processor\\TagProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/TagProcessor.php',
        'Monolog\\Processor\\UidProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/UidProcessor.php',
        'Monolog\\Processor\\WebProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/WebProcessor.php',
        'Monolog\\Registry' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Registry.php',
        'Psr\\Log\\AbstractLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/AbstractLogger.php',
        'Psr\\Log\\InvalidArgumentException' => __DIR__ . '/..' . '/psr/log/Psr/Log/InvalidArgumentException.php',
        'Psr\\Log\\LogLevel' => __DIR__ . '/..' . '/psr/log/Psr/Log/LogLevel.php',
        'Psr\\Log\\LoggerAwareInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareInterface.php',
        'Psr\\Log\\LoggerAwareTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareTrait.php',
        'Psr\\Log\\LoggerInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerInterface.php',
        'Psr\\Log\\LoggerTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerTrait.php',
        'Psr\\Log\\NullLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/NullLogger.php',
        'Psr\\Log\\Test\\DummyTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Psr\\Log\\Test\\LoggerInterfaceTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Sirius\\Validation\\DataWrapper\\ArrayWrapper' => __DIR__ . '/..' . '/siriusphp/validation/src/DataWrapper/ArrayWrapper.php',
        'Sirius\\Validation\\DataWrapper\\WrapperInterface' => __DIR__ . '/..' . '/siriusphp/validation/src/DataWrapper/WrapperInterface.php',
        'Sirius\\Validation\\ErrorMessage' => __DIR__ . '/..' . '/siriusphp/validation/src/ErrorMessage.php',
        'Sirius\\Validation\\Helper' => __DIR__ . '/..' . '/siriusphp/validation/src/Helper.php',
        'Sirius\\Validation\\RuleCollection' => __DIR__ . '/..' . '/siriusphp/validation/src/RuleCollection.php',
        'Sirius\\Validation\\RuleFactory' => __DIR__ . '/..' . '/siriusphp/validation/src/RuleFactory.php',
        'Sirius\\Validation\\Rule\\AbstractRule' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/AbstractRule.php',
        'Sirius\\Validation\\Rule\\AbstractStringRule' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/AbstractStringRule.php',
        'Sirius\\Validation\\Rule\\Alpha' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Alpha.php',
        'Sirius\\Validation\\Rule\\AlphaNumHyphen' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/AlphaNumHyphen.php',
        'Sirius\\Validation\\Rule\\AlphaNumeric' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/AlphaNumeric.php',
        'Sirius\\Validation\\Rule\\ArrayLength' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/ArrayLength.php',
        'Sirius\\Validation\\Rule\\ArrayMaxLength' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/ArrayMaxLength.php',
        'Sirius\\Validation\\Rule\\ArrayMinLength' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/ArrayMinLength.php',
        'Sirius\\Validation\\Rule\\Between' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Between.php',
        'Sirius\\Validation\\Rule\\Callback' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Callback.php',
        'Sirius\\Validation\\Rule\\Date' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Date.php',
        'Sirius\\Validation\\Rule\\DateTime' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/DateTime.php',
        'Sirius\\Validation\\Rule\\Email' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Email.php',
        'Sirius\\Validation\\Rule\\EmailDomain' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/EmailDomain.php',
        'Sirius\\Validation\\Rule\\Equal' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Equal.php',
        'Sirius\\Validation\\Rule\\File\\Extension' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/File/Extension.php',
        'Sirius\\Validation\\Rule\\File\\Image' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/File/Image.php',
        'Sirius\\Validation\\Rule\\File\\ImageHeight' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/File/ImageHeight.php',
        'Sirius\\Validation\\Rule\\File\\ImageRatio' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/File/ImageRatio.php',
        'Sirius\\Validation\\Rule\\File\\ImageWidth' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/File/ImageWidth.php',
        'Sirius\\Validation\\Rule\\File\\Size' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/File/Size.php',
        'Sirius\\Validation\\Rule\\FullName' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/FullName.php',
        'Sirius\\Validation\\Rule\\GreaterThan' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/GreaterThan.php',
        'Sirius\\Validation\\Rule\\InList' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/InList.php',
        'Sirius\\Validation\\Rule\\Integer' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Integer.php',
        'Sirius\\Validation\\Rule\\IpAddress' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/IpAddress.php',
        'Sirius\\Validation\\Rule\\Length' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Length.php',
        'Sirius\\Validation\\Rule\\LessThan' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/LessThan.php',
        'Sirius\\Validation\\Rule\\Match' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Match.php',
        'Sirius\\Validation\\Rule\\MaxLength' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/MaxLength.php',
        'Sirius\\Validation\\Rule\\MinLength' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/MinLength.php',
        'Sirius\\Validation\\Rule\\NotInList' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/NotInList.php',
        'Sirius\\Validation\\Rule\\NotRegex' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/NotRegex.php',
        'Sirius\\Validation\\Rule\\Number' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Number.php',
        'Sirius\\Validation\\Rule\\Regex' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Regex.php',
        'Sirius\\Validation\\Rule\\Required' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Required.php',
        'Sirius\\Validation\\Rule\\RequiredWhen' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/RequiredWhen.php',
        'Sirius\\Validation\\Rule\\RequiredWith' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/RequiredWith.php',
        'Sirius\\Validation\\Rule\\RequiredWithout' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/RequiredWithout.php',
        'Sirius\\Validation\\Rule\\Time' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Time.php',
        'Sirius\\Validation\\Rule\\Upload\\Extension' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Upload/Extension.php',
        'Sirius\\Validation\\Rule\\Upload\\Image' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Upload/Image.php',
        'Sirius\\Validation\\Rule\\Upload\\ImageHeight' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Upload/ImageHeight.php',
        'Sirius\\Validation\\Rule\\Upload\\ImageRatio' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Upload/ImageRatio.php',
        'Sirius\\Validation\\Rule\\Upload\\ImageWidth' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Upload/ImageWidth.php',
        'Sirius\\Validation\\Rule\\Upload\\Size' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Upload/Size.php',
        'Sirius\\Validation\\Rule\\Url' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Url.php',
        'Sirius\\Validation\\Rule\\Website' => __DIR__ . '/..' . '/siriusphp/validation/src/Rule/Website.php',
        'Sirius\\Validation\\Util\\Arr' => __DIR__ . '/..' . '/siriusphp/validation/src/Util/Arr.php',
        'Sirius\\Validation\\Validator' => __DIR__ . '/..' . '/siriusphp/validation/src/Validator.php',
        'Sirius\\Validation\\ValidatorInterface' => __DIR__ . '/..' . '/siriusphp/validation/src/ValidatorInterface.php',
        'Sirius\\Validation\\ValueValidator' => __DIR__ . '/..' . '/siriusphp/validation/src/ValueValidator.php',
        'Upg\\Library\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/AbstractException.php',
        'Upg\\Library\\Api\\AbstractApi' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/AbstractApi.php',
        'Upg\\Library\\Api\\Cancel' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Cancel.php',
        'Upg\\Library\\Api\\Capture' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Capture.php',
        'Upg\\Library\\Api\\CreateTransaction' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/CreateTransaction.php',
        'Upg\\Library\\Api\\DeleteUserPaymentInstrument' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/DeleteUserPaymentInstrument.php',
        'Upg\\Library\\Api\\Exception\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/AbstractException.php',
        'Upg\\Library\\Api\\Exception\\ApiError' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/ApiError.php',
        'Upg\\Library\\Api\\Exception\\CurlError' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/CurlError.php',
        'Upg\\Library\\Api\\Exception\\InvalidHttpResponseCode' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/InvalidHttpResponseCode.php',
        'Upg\\Library\\Api\\Exception\\InvalidUrl' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/InvalidUrl.php',
        'Upg\\Library\\Api\\Exception\\JsonDecode' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/JsonDecode.php',
        'Upg\\Library\\Api\\Exception\\MacValidation' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/MacValidation.php',
        'Upg\\Library\\Api\\Exception\\RequestNotSet' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/RequestNotSet.php',
        'Upg\\Library\\Api\\Exception\\Validation' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Exception/Validation.php',
        'Upg\\Library\\Api\\Finish' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Finish.php',
        'Upg\\Library\\Api\\GetCaptureStatus' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/GetCaptureStatus.php',
        'Upg\\Library\\Api\\GetTransactionStatus' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/GetTransactionStatus.php',
        'Upg\\Library\\Api\\GetUser' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/GetUser.php',
        'Upg\\Library\\Api\\GetUserPaymentInstrument' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/GetUserPaymentInstrument.php',
        'Upg\\Library\\Api\\MacCalculator' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/MacCalculator.php',
        'Upg\\Library\\Api\\Refund' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Refund.php',
        'Upg\\Library\\Api\\RegisterUser' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/RegisterUser.php',
        'Upg\\Library\\Api\\RegisterUserPaymentInstrument' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/RegisterUserPaymentInstrument.php',
        'Upg\\Library\\Api\\Reserve' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/Reserve.php',
        'Upg\\Library\\Api\\UpdateTransaction' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/UpdateTransaction.php',
        'Upg\\Library\\Api\\UpdateUser' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Api/UpdateUser.php',
        'Upg\\Library\\Basket\\BasketItemType' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Basket/BasketItemType.php',
        'Upg\\Library\\Callback\\Exception\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Callback/Exception/AbstractException.php',
        'Upg\\Library\\Callback\\Exception\\MacValidation' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Callback/Exception/MacValidation.php',
        'Upg\\Library\\Callback\\Exception\\ParamNotProvided' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Callback/Exception/ParamNotProvided.php',
        'Upg\\Library\\Callback\\Handler' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Callback/Handler.php',
        'Upg\\Library\\Callback\\MacCalculator' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Callback/MacCalculator.php',
        'Upg\\Library\\Callback\\ProcessorInterface' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Callback/ProcessorInterface.php',
        'Upg\\Library\\Config' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Config.php',
        'Upg\\Library\\Error\\Codes' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Error/Codes.php',
        'Upg\\Library\\Industry\\Codes' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Industry/Codes.php',
        'Upg\\Library\\Locale\\Codes' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Locale/Codes.php',
        'Upg\\Library\\Logging\\Blank' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Logging/Blank.php',
        'Upg\\Library\\Logging\\Factory' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Logging/Factory.php',
        'Upg\\Library\\Mac\\AbstractCalculator' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Mac/AbstractCalculator.php',
        'Upg\\Library\\Mac\\Exception\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Mac/Exception/AbstractException.php',
        'Upg\\Library\\Mac\\Exception\\MacInvalid' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Mac/Exception/MacInvalid.php',
        'Upg\\Library\\Mns\\Exception\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Mns/Exception/AbstractException.php',
        'Upg\\Library\\Mns\\Exception\\ParamNotProvided' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Mns/Exception/ParamNotProvided.php',
        'Upg\\Library\\Mns\\Handler' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Mns/Handler.php',
        'Upg\\Library\\Mns\\ProcessorInterface' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Mns/ProcessorInterface.php',
        'Upg\\Library\\PaymentMethods\\Methods' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/PaymentMethods/Methods.php',
        'Upg\\Library\\Request\\AbstractRequest' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/AbstractRequest.php',
        'Upg\\Library\\Request\\Attributes\\ObjectArray' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Attributes/ObjectArray.php',
        'Upg\\Library\\Request\\Cancel' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Cancel.php',
        'Upg\\Library\\Request\\Capture' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Capture.php',
        'Upg\\Library\\Request\\CreateTransaction' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/CreateTransaction.php',
        'Upg\\Library\\Request\\DeleteUserPaymentInstrument' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/DeleteUserPaymentInstrument.php',
        'Upg\\Library\\Request\\Finish' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Finish.php',
        'Upg\\Library\\Request\\GetCaptureStatus' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/GetCaptureStatus.php',
        'Upg\\Library\\Request\\GetTransactionStatus' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/GetTransactionStatus.php',
        'Upg\\Library\\Request\\GetUser' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/GetUser.php',
        'Upg\\Library\\Request\\GetUserPaymentInstrument' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/GetUserPaymentInstrument.php',
        'Upg\\Library\\Request\\MacCalculator' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/MacCalculator.php',
        'Upg\\Library\\Request\\Objects\\AbstractObject' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/AbstractObject.php',
        'Upg\\Library\\Request\\Objects\\Address' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Address.php',
        'Upg\\Library\\Request\\Objects\\Amount' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Amount.php',
        'Upg\\Library\\Request\\Objects\\Attributes\\Exception\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Attributes/Exception/AbstractException.php',
        'Upg\\Library\\Request\\Objects\\Attributes\\Exception\\FileCouldNotBeFound' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Attributes/Exception/FileCouldNotBeFound.php',
        'Upg\\Library\\Request\\Objects\\Attributes\\File' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Attributes/File.php',
        'Upg\\Library\\Request\\Objects\\Attributes\\FileInterface' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Attributes/FileInterface.php',
        'Upg\\Library\\Request\\Objects\\BasketItem' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/BasketItem.php',
        'Upg\\Library\\Request\\Objects\\Company' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Company.php',
        'Upg\\Library\\Request\\Objects\\CompanyMember' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/CompanyMember.php',
        'Upg\\Library\\Request\\Objects\\HostedPagesText' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/HostedPagesText.php',
        'Upg\\Library\\Request\\Objects\\PaymentInstrument' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/PaymentInstrument.php',
        'Upg\\Library\\Request\\Objects\\Person' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Person.php',
        'Upg\\Library\\Request\\Objects\\Shop' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/Shop.php',
        'Upg\\Library\\Request\\Objects\\ShopInformation' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Objects/ShopInformation.php',
        'Upg\\Library\\Request\\Refund' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Refund.php',
        'Upg\\Library\\Request\\RegisterUser' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/RegisterUser.php',
        'Upg\\Library\\Request\\RegisterUserPaymentInstrument' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/RegisterUserPaymentInstrument.php',
        'Upg\\Library\\Request\\RequestInterface' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/RequestInterface.php',
        'Upg\\Library\\Request\\Reserve' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/Reserve.php',
        'Upg\\Library\\Request\\UpdateTransaction' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Request/UpdateTransaction.php',
        'Upg\\Library\\Response\\AbstractResponse' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/AbstractResponse.php',
        'Upg\\Library\\Response\\FailureResponse' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/FailureResponse.php',
        'Upg\\Library\\Response\\SuccessResponse' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/SuccessResponse.php',
        'Upg\\Library\\Response\\Unserializer\\Factory' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Factory.php',
        'Upg\\Library\\Response\\Unserializer\\Handler\\Address' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Handler/Address.php',
        'Upg\\Library\\Response\\Unserializer\\Handler\\Amount' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Handler/Amount.php',
        'Upg\\Library\\Response\\Unserializer\\Handler\\ArrayPaymentInstruments' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Handler/ArrayPaymentInstruments.php',
        'Upg\\Library\\Response\\Unserializer\\Handler\\Company' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Handler/Company.php',
        'Upg\\Library\\Response\\Unserializer\\Handler\\PaymentInstruments' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Handler/PaymentInstruments.php',
        'Upg\\Library\\Response\\Unserializer\\Handler\\Person' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Handler/Person.php',
        'Upg\\Library\\Response\\Unserializer\\Handler\\UnserializerInterface' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Handler/UnserializerInterface.php',
        'Upg\\Library\\Response\\Unserializer\\Processor' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Response/Unserializer/Processor.php',
        'Upg\\Library\\Risk\\RiskClass' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Risk/RiskClass.php',
        'Upg\\Library\\Serializer\\Exception\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Exception/AbstractException.php',
        'Upg\\Library\\Serializer\\Exception\\VisitorCouldNotBeFound' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Exception/VisitorCouldNotBeFound.php',
        'Upg\\Library\\Serializer\\Serializer' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Serializer.php',
        'Upg\\Library\\Serializer\\SerializerFactory' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/SerializerFactory.php',
        'Upg\\Library\\Serializer\\Visitors\\AbstractVisitor' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Visitors/AbstractVisitor.php',
        'Upg\\Library\\Serializer\\Visitors\\Exception\\AbstractException' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Visitors/Exception/AbstractException.php',
        'Upg\\Library\\Serializer\\Visitors\\Json' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Visitors/Json.php',
        'Upg\\Library\\Serializer\\Visitors\\MultipartFormData' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Visitors/MultipartFormData.php',
        'Upg\\Library\\Serializer\\Visitors\\UrlEncode' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Visitors/UrlEncode.php',
        'Upg\\Library\\Serializer\\Visitors\\VisitorInterface' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Serializer/Visitors/VisitorInterface.php',
        'Upg\\Library\\User\\Type' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/User/Type.php',
        'Upg\\Library\\Validation\\Helper\\Constants' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Validation/Helper/Constants.php',
        'Upg\\Library\\Validation\\Helper\\Regex' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Validation/Helper/Regex.php',
        'Upg\\Library\\Validation\\Validation' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Validation/Validation.php',
        'Upg\\Library\\Validation\\WrapperInterface' => __DIR__ . '/..' . '/upgplc/php-clientlibrary/src/Validation/WrapperInterface.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita6a8066d8501c06ca5ddb4b01cd61a77::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita6a8066d8501c06ca5ddb4b01cd61a77::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita6a8066d8501c06ca5ddb4b01cd61a77::$classMap;

        }, null, ClassLoader::class);
    }
}
