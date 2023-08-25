<?php
namespace TgoeSrv\Document;

use TgoeSrv\Member\MemberGroup;
use Aspose\Words\Model\UserInformation;
use Aspose\Words\Model\Requests\ExecuteMailMergeOnlineRequest;
use Aspose\Words\Model\FieldOptions;
use TgoeSrv\Member\Enums\MemberGroupCustomProperty;

class GroupMemberDataConfirmationListService
{

    private const TEMPLATE_FILE = 'GroupMemberDataConfirmationList.docx';

    public static function generateDocument(MemberGroup $group, array $members)
    {
        $wordsApi = AsposeCloudFactory::getAsposeWords();

        $requestTemplate = AsposeCloudFactory::getTemplateDirectory(self::TEMPLATE_FILE);
        $requestOptionsCurrentUser = new UserInformation(array(
            "name" => "SdkTestUser"
        ));
        $requestOptions = new FieldOptions(array(
            "current_user" => $requestOptionsCurrentUser
        ));

        $data = [
            'groupname' => $group->getName(),
            'trainer' => $group->getCustomProperty(MemberGroupCustomProperty::TRAINER),
            'weekday' => $group->getCustomProperty(MemberGroupCustomProperty::WEEKDAY),
            'time' => $group->getCustomProperty(MemberGroupCustomProperty::TIME),
            'location' => $group->getCustomProperty(MemberGroupCustomProperty::LOCATION),
            'printdate' => date('d.m.Y'),
            'count' => count($members),
        ];

        // write data to tempfile
        // TODO: isn't it possible to hand over as a string?
        $th = tmpfile();
        fwrite($th, json_encode($data));
        $filedata = stream_get_meta_data($th);
        $requestData = $filedata['uri'];
        // fclose($th);

        $mailMergeRequest = new ExecuteMailMergeOnlineRequest($requestTemplate, $requestData, $requestOptions, NULL, NULL, NULL);
        $response = $wordsApi->executeMailMergeOnline($mailMergeRequest);

        if( $response instanceof  \SplFileObject) {
            return $response->getPathname();
        }
        
        return null;
    }
}

