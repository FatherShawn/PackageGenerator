<?php
/**
 * Class which returns the class map definition
 * @package
 */
class ClassMap
{
    /**
     * Returns the mapping between the WSDL Structs and generated Structs' classes
     * This array is sent to the \SoapClient when calling the WS
     * @return string[]
     */
    final public static function get()
    {
        return array(
            'base64Binary' => 'Base64Binary',
            'hexBinary' => 'HexBinary',
            'AttachmentType' => 'AttachmentType',
            'SessionHeader' => 'SessionHeader',
            'ClusterHeader' => 'ClusterHeader',
            'PartnerHeader' => 'PartnerHeader',
            'ActonFaultType' => 'ActonFaultType',
            'LoginResult' => 'LoginResult',
            'login' => 'Login',
            'loginResponse' => 'LoginResponse',
            'list' => '_list',
            'listResponse' => 'ListResponse',
            'Item' => 'Item',
            'sendEmail' => 'SendEmail',
            'CustomEmail' => 'CustomEmail',
            'launchParameters' => 'LaunchParameters',
            'senderFrom' => 'SenderFrom',
            'sendEmailResponse' => 'SendEmailResponse',
            'UploadContactRecordType' => 'UploadContactRecordType',
            'uploadList' => 'UploadList',
            'columnDef' => 'ColumnDef',
            'mergeOptions' => 'MergeOptions',
            'columnMap' => 'ColumnMap',
            'uploadListResponse' => 'UploadListResponse',
            'getUploadResultRequest' => 'GetUploadResultRequest',
            'getUploadResultResponse' => 'GetUploadResultResponse',
            'appendedRecords' => 'AppendedRecords',
            'updatedRecords' => 'UpdatedRecords',
            'unchangedRecords' => 'UnchangedRecords',
            'downloadList' => 'DownloadList',
            'deleteList' => 'DeleteList',
            'messageReport' => 'MessageReport',
            'fullDetail' => 'FullDetail',
            'contactFields' => 'ContactFields',
            'messageReportResponse' => 'MessageReportResponse',
            'summary' => 'Summary',
            'uniqueCounts' => 'UniqueCounts',
            'totalCounts' => 'TotalCounts',
            'messageInfo' => 'MessageInfo',
            'detail' => 'Detail',
            'actionRecordDetailType' => 'ActionRecordDetailType',
            'actionRecord' => 'ActionRecord',
            'contactField' => 'ContactField',
        );
    }
}
