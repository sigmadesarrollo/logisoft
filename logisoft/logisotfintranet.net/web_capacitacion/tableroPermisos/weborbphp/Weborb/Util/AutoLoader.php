<?php
class AutoLoader
{
	private $pathes;
	
	public static $instance;
	
	public static function getInstance()
	{
		if (is_null(self::$instance))
        {
            self::$instance = new AutoLoader();            
        }
        
        return self::$instance;
	}
	
	public function __construct()
	{
		$this->pathes = array(
			//Config
			'ServicesConfigHandler' => WebOrb . "Config/ServicesConfigHandler.php",
			'ClassMappingsHandler' => WebOrb . "Config/ClassMappingsHandler.php",
			'LoggingConfigHandler' => WebOrb . "Config/LoggingConfigHandler.php",
			'FlexRemotingServiceConfig' => WebOrb . "Config/FlexRemotingServiceConfig.php",
			'FlexMessagingServiceConfig' => WebOrb . "Config/FlexMessagingServiceConfig.php",
			'BusinessIntelligenceConfig' => WebOrb . "Config/BusinessIntelligenceConfig.php",
			'ORBConfigHandler' => WebOrb . "Config/ORBConfigHandler.php",
			'BaseFlexConfig' => WebOrb . "Config/BaseFlexConfig.php",
			'ORBConfig' => WebOrb . "Config/ORBConfig.php",
			'IConfigurationSectionHandler' => WebOrb . "Config/IConfigurationSectionHandler.php",
			'ChannelRegistry' => WebOrb . "Config/ChannelRegistry.php",
		
			//Dispatch
			'Dispatchers' => WebOrb . "Dispatch/Dispatchers.php",
			'Inspector' => WebOrb . "Dispatch/Inspector.php",
			'Invoker' => WebOrb . "Dispatch/Invoker.php",
			'V3Dispatcher' => WebOrb . "Dispatch/V3Dispatcher.php",
			'IDispatch' => WebOrb . "Dispatch/IDispatch.php",
		
			//Exceptions
			'InspectionException' => WebOrb . "Exceptions/InspectionException.php",
			'ServiceException' => WebOrb . "Exceptions/ServiceException.php",
			'ArgumentException' => WebOrb . "Exceptions/ArgumentException.php",
			'ConfigurationException' => WebOrb . "Exceptions/ConfigurationException.php",
			'InvocationException' => WebOrb . "Exceptions/InvocationException.php",
			'LicenseException' => WebOrb . "Exceptions/LicenseException.php",
			'UnknownRequestFormatException' => WebOrb . "Exceptions/UnknownRequestFormatException.php",
			'WebORBAuthenticationException' => WebOrb . "Exceptions/WebORBAuthenticationException.php",
			'ApplicationException' => WebOrb . "Exceptions/ApplicationException.php",
			
			//Handlers
			'ObjectHandler' => WebOrb . "Handlers/ObjectHandler.php",
			'Handlers' => WebOrb . "Handlers/Handlers.php",
			'IInvocationHandler' => WebOrb . "Handlers/IInvocationHandler.php",
			'IInspectionHandler' => WebOrb . "Handlers/IInspectionHandler.php",
			'WebServiceHandler' => WebOrb . "Handlers/WebServiceHandler.php",
		
			//Inspection
			'ArgumentDescriptor' => WebOrb . "Inspection/ArgumentDescriptor.php",
			'InspectionConstants' => WebOrb . "Inspection/InspectionConstants.php",
			'MethodDescriptor' => WebOrb . "Inspection/MethodDescriptor.php",
			'ServiceDescriptor' => WebOrb . "Inspection/ServiceDescriptor.php",
			
			//Message
			'Body' => WebOrb . "Message/Body.php",
			'Request' => WebOrb . "Message/Request.php",
			'Header' => WebOrb . "Message/Header.php",
		
			//messaging/v3
			'MessagingDestination' => WebOrb . "messaging/v3/MessagingDestination.php",
			'IMessageSelector' => WebOrb . "messaging/v3/IMessageSelector.php",
			'IMessageStoragePolicy' => WebOrb . "messaging/v3/IMessageStoragePolicy.php",
			'MemoryStoragePolicy' => WebOrb . "messaging/v3/MemoryStoragePolicy.php",
			'MessagingServiceHandler' => WebOrb . "messaging/v3/MessagingServiceHandler.php",
			'PendingMessage' => WebOrb . "messaging/v3/PendingMessage.php",
			'PersistentStoragePolicy' => WebOrb . "messaging/v3/PersistentStoragePolicy.php",
			'Subscriber' => WebOrb . "messaging/v3/Subscriber.php",
			'SubscriptionsManager' => WebOrb . "messaging/v3/SubscriptionsManager.php",
			
		
			//Protocols		
			'ProtocolRegistry'  => WebOrb . "Protocols/ProtocolRegistry.php",
			'AMFMessageFactory' => WebOrb . "Protocols/AMFMessageFactory.php",
			'IMessageFactory' => WebOrb . "Protocols/IMessageFactory.php",
			//Protocols/Wolf
			'RequestParser' => WebOrb . "Protocols/Wolf/RequestParser.php",
			
			//Reader
			'AnonymousObject' => WebOrb . "Reader/AnonymousObject.php",
			'AnonymousObjectReader' => WebOrb . "Reader/AnonymousObjectReader.php",
			'ArrayReader' => WebOrb . "Reader/ArrayReader.php",
			'ArrayType' => WebOrb . "Reader/ArrayType.php",
			'BooleanReader' => WebOrb . "Reader/BooleanReader.php",
			'BooleanType' => WebOrb . "Reader/BooleanType.php",
			'BoundPropertyBagReader' => WebOrb . "Reader/BoundPropertyBagReader.php",
			'ClassInfo' => WebOrb . "Reader/ClassInfo.php",
			'ConcreteObject' => WebOrb . "Reader/ConcreteObject.php",
			'DateReader' => WebOrb . "Reader/DateReader.php",
			'DateType' => WebOrb . "Reader/DateType.php",
			'FlashorbBinaryReader' => WebOrb . "Reader/FlashorbBinaryReader.php",
			'IAdaptingType' => WebOrb . "Reader/IAdaptingType.php",
			'ICacheableAdaptingType' => WebOrb . "Reader/ICacheableAdaptingType.php",
			'IntegerReader' => WebOrb . "Reader/IntegerReader.php",
			'ITypeReader' => WebOrb . "Reader/ITypeReader.php",
			'IXMLTypeReader' => WebOrb . "Reader/IXMLTypeReader.php",
			'LongUTFStringReader' => WebOrb . "Reader/LongUTFStringReader.php",
			'MessageDataReader' => WebOrb . "Reader/MessageDataReader.php",
			'NamedObject' => WebOrb . "Reader/NamedObject.php",
			'NamedObjectReader' => WebOrb . "Reader/NamedObjectReader.php",
			'NotAReader' => WebOrb . "Reader/NotAReader.php",
			'NullReader' => WebOrb . "Reader/NullReader.php",
			'NullType' => WebOrb . "Reader/NullType.php",
			'NumberObject' => WebOrb . "Reader/NumberObject.php",
			'NumberReader' => WebOrb . "Reader/NumberReader.php",
			'ParseContext' => WebOrb . "Reader/ParseContext.php",
			'PointerReader' => WebOrb . "Reader/PointerReader.php",
			'ReaderUtils' => WebOrb . "Reader/ReaderUtils.php",
			'ReferenceCache' => WebOrb . "Reader/ReferenceCache.php",
			'RemoteReferenceObject' => WebOrb . "Reader/RemoteReferenceObject.php",
			'RemoteReferenceReader' => WebOrb . "Reader/RemoteReferenceReader.php",
			'StringType' => WebOrb . "Reader/StringType.php",
			'UndefinedType' => WebOrb . "Reader/UndefinedType.php",
			'UndefinedTypeReader' => WebOrb . "Reader/UndefinedTypeReader.php",
			'UTFStringReader' => WebOrb . "Reader/UTFStringReader.php",
			'V3ArrayReader' => WebOrb . "Reader/V3ArrayReader.php",
			'V3ByteArrayReader' => WebOrb . "Reader/V3ByteArrayReader.php",
			'V3DateReader' => WebOrb . "Reader/V3DateReader.php",
			'V3ObjectReader' => WebOrb . "Reader/V3ObjectReader.php",
			'V3Reader' => WebOrb . "Reader/V3Reader.php",
			'V3StringReader' => WebOrb . "Reader/V3StringReader.php",
			'V3XmlReader' => WebOrb . "Reader/V3XmlReader.php",
			'XmlDataReader' => WebOrb . "Reader/XmlDataReader.php",
			'XmlDataType' => WebOrb . "Reader/XmlDataType.php",
			//'' => WebOrb . "Reader/.php",
			//Reader/Dataset
			'DataSetInfo' => WebOrb . "Reader/Dataset/DataSetInfo.php",
			'RemotingDataSet' => WebOrb . "Reader/Dataset/RemotingDataSet.php",
			//Reader/Wolf
			'ArrayWolfReader' => WebOrb . "Reader/Wolf/ArrayWolfReader.php",
			'BooleanWolfReader' => WebOrb . "Reader/Wolf/BooleanWolfReader.php",
			'DateWolfReader' => WebOrb . "Reader/Wolf/DateWolfReader.php",
			'NullWolfReader' => WebOrb . "Reader/Wolf/NullWolfReader.php",
			'NumberWolfReader' => WebOrb . "Reader/Wolf/NumberWolfReader.php",
			'ObjectWolfReader' => WebOrb . "Reader/Wolf/ObjectWolfReader.php",
			'ReferenceWolfReader' => WebOrb . "Reader/Wolf/ReferenceWolfReader.php",
			'StringWolfReader' => WebOrb . "Reader/Wolf/StringWolfReader.php",
			'XmlWolfReader' => WebOrb . "Reader/Wolf/XmlWolfReader.php",
			
			//Registry			
			'ServiceRegistry' => WebOrb . "Registry/ServiceRegistry.php",
			'MonitoredClassRegistry' => WebOrb . "Registry/MonitoredClassRegistry.php",
			'MonitoredClass' => WebOrb . "Registry/MonitoredClass.php",
			
			//Security
			'AccessConstraint' => WebOrb . "Security/AccessConstraint.php",
			'AclConfigHandler' => WebOrb . "Security/AclConfigHandler.php",
			'AnyTokenComparator' => WebOrb . "Security/AnyTokenComparator.php",
			'Credentials' => WebOrb . "Security/Credentials.php",
			'HostNameRestriction' => WebOrb . "Security/HostNameRestriction.php",
			'IAuthenticationHandler' => WebOrb . "Security/IAuthenticationHandler.php",
			'IAuthorizationHandler' => WebOrb . "Security/IAuthorizationHandler.php",
			'IPRangeRestriction' => WebOrb . "Security/IPRangeRestriction.php",
			'IRestriction' => WebOrb . "Security/IRestriction.php",
			'IRolesProvider' => WebOrb . "Security/IRolesProvider.php",
			'ITokenComparator' => WebOrb . "Security/ITokenComparator.php",
			'ORBSecurity' => WebOrb . "Security/ORBSecurity.php",
			'RoleNameRestriction' => WebOrb . "Security/RoleNameRestriction.php",
			'SecurityConfigHandler' => WebOrb . "Security/SecurityConfigHandler.php",
			'SingleIPRestriction' => WebOrb . "Security/SingleIPRestriction.php",
			'TokenComparator' => WebOrb . "Security/TokenComparator.php",
			'WebORBAuthenticationHandler' => WebOrb . "Security/WebORBAuthenticationHandler.php",
			'WebORBAuthorizationHandler' => WebOrb . "Security/WebORBAuthorizationHandler.php",
			'WebORBRolesProvider' => WebOrb . "Security/WebORBRolesProvider.php",
			
			
			//Types
			'Types' => WebOrb . "Types/Types.php",
			
			//Util
			'ClassInspector' => WebOrb . "Util/ClassInspector.php",
			'DatabaseReaderFactory' => WebOrb . "Util/DatabaseReaderFactory.php",
			'Datatypes' => WebOrb . "Util/Datatypes.php",
			'Hashtable' => WebOrb . "Util/Hashtable.php",
			'IDatabaseReader' => WebOrb . "Util/IDatabaseReader.php",
			'Invocation' => WebOrb . "Util/Invocation.php",
			'MethodLookup' => WebOrb . "Util/MethodLookup.php",
			'Network' => WebOrb . "Util/Network.php",
			'ObjectFactories' => WebOrb . "Util/ObjectFactories.php",
			'ORBDateTime' => WebOrb . "Util/ORBDateTime.php",
			'Paths' => WebOrb . "Util/Paths.php",
			'ThreadContext' => WebOrb . "Util/ThreadContext.php",
			'TypeLoader' => WebOrb . "Util/TypeLoader.php",
			'Value' => WebOrb . "Util/Value.php",
			'XmlUtil' => WebOrb . "Util/XmlUtil.php",
			'WebServiceInvocation' => WebOrb . "Util/WebServiceInvocation.php",
			//Util/Cache
			'Cache' => WebOrb . "Util/Cache/Cache.php",
			//Util/DatabaseReaders
			'MsSqlDatabaseReader' => WebOrb . "Util/DatabaseReaders/MsSqlDatabaseReader.php",
			'MySqlDatabaseReader' => WebOrb . "Util/DatabaseReaders/MySqlDatabaseReader.php",
			//Util/Fpdf
			'FPDF' => WebOrb . "Util/Fpdf/fpdf.php",
			//Util/Logging
			'AbstractLogger' => WebOrb . "Util/Logging/AbstractLogger.php",
			'DateLogger' => WebOrb . "Util/Logging/DateLogger.php",
			'ILogger' => WebOrb . "Util/Logging/ILogger.php",
			'ILoggingPolicy' => WebOrb . "Util/Logging/ILoggingPolicy.php",
			'Log' => WebOrb . "Util/Logging/Log.php",
			'LoggingConstants' => WebOrb . "Util/Logging/LoggingConstants.php",
			'SizeThresholdLogger' => WebOrb . "Util/Logging/SizeThresholdLogger.php",
			'TraceLogger' => WebOrb . "Util/Logging/TraceLogger.php",
			//Util/Logging/Policies
			'DatePolicy' => WebOrb . "Util/Logging/Policies/DatePolicy.php",
			'LoggingPolicyFactory' => WebOrb . "Util/Logging/Policies/LoggingPolicyFactory.php",
			'SizeThresholdPolicy' => WebOrb . "Util/Logging/Policies/SizeThresholdPolicy.php",
			'SpecificFilePolicy' => WebOrb . "Util/Logging/Policies/SpecificFilePolicy.php",
			//Utill/WebServiceGenerator
			'WebServiceDescriptor' => WebOrb . "Util/WebServiceGenerator/WebServiceDescriptor.php",
			'WebServiceGenerator' => WebOrb . "Util/WebServiceGenerator/WebServiceGenerator.php",
			'WebServiceInspector' => WebOrb . "Util/WebServiceGenerator/WebServiceInspector.php",
			'WebServiceMethod' => WebOrb . "Util/WebServiceGenerator/WebServiceMethod.php",
			'WebServiceType' => WebOrb . "Util/WebServiceGenerator/WebServiceType.php",
			//Util/ZIP
			'CreateArc' => WebOrb . "Util/ZIP/CreateArc.php",
			//Util/Nusoap
			'soapclient' => WebOrb . "Util/Nusoap/nusoap.php",
		
			//V3Types
			'AckMessage' => WebOrb . "V3Types/AckMessage.php",
			'AsyncMessage' => WebOrb . "V3Types/AsyncMessage.php",
			'BodyHolder' => WebOrb . "V3Types/BodyHolder.php",
			'CommandMessage' => WebOrb . "V3Types/CommandMessage.php",
			'DataMessage' => WebOrb . "V3Types/DataMessage.php",
			'ErrDataMessage' => WebOrb . "V3Types/ErrDataMessage.php",
			'ErrMessage' => WebOrb . "V3Types/ErrMessage.php",
			'GUID' => WebOrb . "V3Types/GUID.php",
			'PagedMessage' => WebOrb . "V3Types/PagedMessage.php",
			'ReqMessage' => WebOrb . "V3Types/ReqMessage.php",
			'SeqMessage' => WebOrb . "V3Types/SeqMessage.php",
			'V3Message' => WebOrb . "V3Types/V3Message.php",
			//V3Types/core
			'AbstractDestination' => WebOrb . "V3Types/core/AbstractDestination.php",
			'Channel' => WebOrb . "V3Types/core/Channel.php",
			'DataServices' => WebOrb . "V3Types/core/DataServices.php",
			'DestinationManager' => WebOrb . "V3Types/core/DestinationManager.php",
			'IAdapter' => WebOrb . "V3Types/core/IAdapter.php",
			'IDestination' => WebOrb . "V3Types/core/IDestination.php",
			'IMessageEventListener' => WebOrb . "V3Types/core/IMessageEventListener.php",
			'IServiceHandler' => WebOrb . "V3Types/core/IServiceHandler.php",
			'RemotingDestination' => WebOrb . "V3Types/core/RemotingDestination.php",
			
			
			//Writer
			'AMFBodyWriter' => WebOrb . "Writer/AMFBodyWriter.php",
			'AmfFormatter' => WebOrb . "Writer/AmfFormatter.php",
			'AMFHeaderWriter' => WebOrb . "Writer/AMFHeaderWriter.php",
			'AMFMessageWriter' => WebOrb . "Writer/AMFMessageWriter.php",
			'AmfV3Formatter' => WebOrb . "Writer/AmfV3Formatter.php",
			'ArrayObjectWriter' => WebOrb . "Writer/ArrayObjectWriter.php",
			'ArrayWriter' => WebOrb . "Writer/ArrayWriter.php",
			'BaseFormatter' => WebOrb . "Writer/BaseFormatter.php",
			'BodyHolderWriter' => WebOrb . "Writer/BodyHolderWriter.php",
			'BooleanWriter' => WebOrb . "Writer/BooleanWriter.php",
			'BoundPropertyBagWriter' => WebOrb . "Writer/BoundPropertyBagWriter.php",
			'DataTable' => WebOrb . "Writer/DataTable.php",
			'DataTableAsListWriter' => WebOrb . "Writer/DataTableAsListWriter.php",
			'DateWriter' => WebOrb . "Writer/DateWriter.php",
			'FlashorbBinaryWriter' => WebOrb . "Writer/FlashorbBinaryWriter.php",
			'IObjectSerializer' => WebOrb . "Writer/IObjectSerializer.php",
			'IProtocolFormatter' => WebOrb . "Writer/IProtocolFormatter.php",
			'IRemote' => WebOrb . "Writer/IRemote.php",
			'ITypeWriter' => WebOrb . "Writer/ITypeWriter.php",
			'IUseDirectFieldAccess' => WebOrb . "Writer/IUseDirectFieldAccess.php",
			'MessageWriter' => WebOrb . "Writer/MessageWriter.php",
			'NullWriter' => WebOrb . "Writer/NullWriter.php",
			'NumberWriter' => WebOrb . "Writer/NumberWriter.php",
			'ObjectSerializer' => WebOrb . "Writer/ObjectSerializer.php",
			'ObjectWriter' => WebOrb . "Writer/ObjectWriter.php",
			'ObjectWriterCache' => WebOrb . "Writer/ObjectWriterCache.php",
			'ORBDateTimeWriter' => WebOrb . "Writer/ORBDateTimeWriter.php",
			'ORBXMLWriter' => WebOrb . "Writer/ORBXMLWriter.php",
			'ReferenceCache' => WebOrb . "Writer/ReferenceCache.php",
			'ReferenceWriter' => WebOrb . "Writer/ReferenceWriter.php",
			'RemoteReferenceWriter' => WebOrb . "Writer/RemoteReferenceWriter.php",
			'ResourceWriter' => WebOrb . "Writer/ResourceWriter.php",
			'StdClassWriter' => WebOrb . "Writer/StdClassWriter.php",
			'StringWriter' => WebOrb . "Writer/StringWriter.php",
			'V3ObjectSerializer' => WebOrb . "Writer/V3ObjectSerializer.php",
			'V3ReferenceCache' => WebOrb . "Writer/V3ReferenceCache.php",
			//Writer/Wolf
			'WolfFormatter' => WebOrb . "Writer/Wolf/WolfFormatter.php",
		
			//
			'ORBConstants' => WebOrb . "ORBConstants.php",
			'IRemote' => WebOrb . "IRemote.php",
			'PHPErrorHandler' => WebOrb . "PHPErrorHandler.php",			
			
			//../Services
			'ServiceNode' => WebOrb . "../Services//Weborb/Management/ServiceBrowser/ServiceNode.php",
			'ServerConfiguration' => WebOrb . "../Services/Weborb/Management/RBIManagement/ServerConfiguration.php",
			
			
			
			
			
			
			
			
			
			
			
			
			
			
					
			
			
			
		);
		$this->pathes = array_unique($this->pathes);
	}
	
	public function load($className)
	{
		$path = $this->pathes[$className];
		if(file_exists($path))
			require_once($path);
		else
			Log::log(LoggingConstants::EXCEPTION, "Cannot load file: " . $path);
	}
	
}
?>