<?php

namespace App\Http\Controllers;

use App\Events\CatchException;
use Validator;
use App\Business\IMSport;
use App\Repository\IMSport as RepositoryIMSport;
use Illuminate\Http\Request;

class IMSportController extends Controller
{
    // 索取所有體育計數
    public function getAllSportCount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID', // 目前系統不支援 CHT (繁中)
                'IsCombo' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $isCombo = $request->input('IsCombo');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getAllSportCount($languageCode, $isCombo);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取所有競賽計數
    public function getAllCompetitionCount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'SportId' => 'required',
                'IsCombo' => 'required',
                'Market' => 'required',
                'EventDate' => 'nullable',
                'EventGroupTypeIds' => 'nullable',
                'ProgrammeId' => 'nullable',
                'IncludeCloseEvent' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $sportId = $request->input('SportId');
            $isCombo = $request->input('IsCombo');
            $market = $request->input('Market');
            $eventDate = $request->input('EventDate');
            $eventGroupTypeIds = $request->input('EventGroupTypeIds');
            $programmeId = $request->input('ProgrammeId');
            $includeCloseEvent = $request->input('IncludeCloseEvent');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getAllCompetitionCount(
                $languageCode,
                $sportId,
                $isCombo,
                $market,
                $includeCloseEvent,
                $eventDate,
                $eventGroupTypeIds,
                $programmeId
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取賽事資料
    public function getEventInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'Market' => 'required',
                'BetTypeIds' => 'nullable|array',
                'EventDate' => 'nullable|date',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'EventGroupTypeIds' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $market = $request->input('Market');
            $betTypeIds = $request->input('BetTypeIds');
            $eventDate = $request->input('EventDate');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? 'CHS';
            $eventGroupTypeIds = $request->input('EventGroupTypeIds');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getEventInfo($sportId, $market, $betTypeIds, $eventDate, $languageCode, $eventGroupTypeIds);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取DELTA賽事詳情
    public function getDeltaEventInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'Market' => 'required',
                'Delta' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $market = $request->input('Market');
            $delta = $request->input('Delta');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getDeltaEventInfo($sportId, $market, $delta);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 以頁數索取賽事資料
    public function getEventInfoByPage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'Market' => 'required',
                'EventDate' => 'nullable',
                'BetTypeIds' => 'nullable',
                'PeriodIds' => 'nullable',
                'MarketlineLevels' => 'nullable',
                'EventGroupTypeId' => 'nullable',
                'OddsType' => 'required',
                'IsCombo' => 'required',
                'OrderBy' => 'nullable',
                'CompetitionIds' => 'nullable',
                'Page' => 'required',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $market = $request->input('Market');
            $eventDate = $request->input('EventDate');
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');
            $marketLineLevels = $request->input('MarketlineLevels');
            $eventGroupTypeId = $request->input('EventGroupTypeId');
            $oddsType = $request->input('OddsType');
            $isCombo = $request->input('IsCombo');
            $orderBy = $request->input('OrderBy');
            $competitionIds = $request->input('CompetitionIds');
            $page = $request->input('Page');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->getEventInfoByPage(
                $sportId,
                $market,
                $oddsType,
                $isCombo,
                $page,
                $languageCode,
                $eventDate,
                $betTypeIds,
                $orderBy,
                $periodIds,
                $marketLineLevels,
                $eventGroupTypeId,
                $competitionIds,
                $token,
                $memberCode
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取滾球賽事資料
    public function getLiveEventInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportIds' => 'required',
                'BetTypeIds' => 'nullable',
                'PeriodIds' => 'nullable',
                'MarketlineLevels' => 'nullable',
                'EventGroupTypeId' => 'nullable',
                'OddsType' => 'required',
                'IsCombo' => 'required',
                'OrderBy' => 'nullable',
                'CompetitionIds' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportIds = $request->input('SportIds');
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');
            $marketLineLevels = $request->input('MarketlineLevels');
            $eventGroupTypeId = $request->input('EventGroupTypeId');
            $oddsType = $request->input('OddsType');
            $isCombo = $request->input('IsCombo');
            $orderBy = $request->input('OrderBy');
            $competitionIds = $request->input('CompetitionIds');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->getLiveEventInfo(
                $sportIds,
                $oddsType,
                $isCombo,
                $languageCode,
                $betTypeIds,
                $orderBy,
                $periodIds,
                $marketLineLevels,
                $eventGroupTypeId,
                $competitionIds,
                $token,
                $memberCode
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取賽事選項資料
    public function getSelectedEventInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'EventIds' => 'required',
                'BetTypeIds' => 'nullable',
                'PeriodIds' => 'nullable',
                'OddsType' => 'required',
                'IsCombo' => 'required',
                'IncludeGroupEvents' => 'required',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $eventIds = $request->input('EventIds');
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');
            $oddsType = $request->input('OddsType');
            $isCombo = $request->input('IsCombo');
            $includeGroupEvents = $request->input('IncludeGroupEvents');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->getSelectedEventInfo(
                $sportId,
                $eventIds,
                $oddsType,
                $isCombo,
                $includeGroupEvents,
                $languageCode,
                $betTypeIds,
                $periodIds,
                $token,
                $memberCode
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取熱門賽事
    public function getPopularEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportIds' => 'nullable',
                'OddsType' => 'required',
                'BetTypeIds' => 'nullable',
                'PeriodIds' => 'nullable',
                'MarketlineLevels' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'IsCombo' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $sportIds = $request->input('SportIds');
            $oddsType = $request->input('OddsType');
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');
            $marketLineLevels = $request->input('MarketlineLevels');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $isCombo = $request->input('IsCombo');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getPopularEvent(
                $oddsType, $languageCode, $isCombo, $token, $memberCode,
                $sportIds, $betTypeIds, $periodIds, $marketLineLevels
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取優勝冠軍賽事
    public function getOutrightEvents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'ProgrammeId' => 'nullable',
                'IsCombo' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $programmeId = $request->input('ProgrammeId');
            $isCombo = $request->input('IsCombo');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getOutrightEvents($sportId, $languageCode, $isCombo, $programmeId);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取DELTA優勝冠軍賽事
    public function getDeltaOutrightEventInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'ProgrammeId' => 'nullable',
                'IsCombo' => 'required',
                'Delta' => 'required',
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $programmeId = $request->input('ProgrammeId');
            $isCombo = $request->input('IsCombo');
            $delta = $request->input('Delta');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getDeltaOutrightEventInfo($sportId, $isCombo, $delta, $languageCode, $programmeId);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 搜索
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'Filter' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'IsCombo' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $filter = $request->input('Filter');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $isCombo = $request->input('IsCombo');

            $imSport = new RepositoryIMSport();

            $data = $imSport->search($sportId, $filter, $languageCode, $isCombo);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取定位
    public function getLocalizations(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LocalizationType' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $localizationType = $request->input('LocalizationType');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getLocalizations($localizationType);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取 DELTA 定位
    public function getDeltaLocalizations(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LocalizationType' => 'required',
                'Delta' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $localizationType = $request->input('LocalizationType');
            $delta = $request->input('Delta');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getDeltaLocalizations($localizationType, $delta);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取完整赛果
    public function getCompletedResults(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'EventTypeId' => 'required',
                'StartDate' => 'required',
                'EndDate' => 'required',
                'CompetitionId' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $eventTypeId = $request->input('EventTypeId');
            $startDate = $request->input('StartDate');
            $endDate = $request->input('EndDate');
            $competitionId = $request->input('CompetitionId');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->getCompletedResults(
                $sportId,
                $eventTypeId,
                $startDate,
                $endDate,
                $languageCode,
                $competitionId
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 延遲操作會話
    public function extendSession(Request $request)
    {
        try {
            $token = $request->cookie('abs-token') ?? $request->input('Token');

            $imSport = new RepositoryIMSport();

            $data = $imSport->extendSession($token);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 會員登出
    public function logOut(Request $request)
    {
        try {
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $imSport = new RepositoryIMSport();

            $data = $imSport->logOut($token, $memberCode);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取投注信息
    public function getBetInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'WagerType' => 'required',
                'WagerSelectionInfos' => 'required',
                'ReturnNearestHandicap' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $wagerType = $request->input('WagerType');
            $wagerSelectionInfos = $request->input('WagerSelectionInfos');
            $returnNearestHandicap = $request->input('ReturnNearestHandicap');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getBetInfo(
                $wagerType,
                $wagerSelectionInfos,
                $token,
                $memberCode,
                $returnNearestHandicap
            );

            // 注單狀態只抓內層, 內層任何一張單有狀況就會回傳錯誤代碼 (381賠率變更以外)
            $wagerCode = $data['StatusCode'];
            foreach ($data['WagerSelectionInfos'] ?? [] as $wagerInfo) {
                if ($wagerInfo['Status'] != 100 and $wagerInfo['Status'] != 381) {
                    $wagerCode = $wagerInfo['Status'];
                    break;
                }
            }
            if ($wagerCode != 100) {
                throw new \Exception(null, $wagerCode);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 投注
    public function placeBet(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'WagerType' => 'required',
                'CustomerMacAddress' => 'nullable',
                'AppDeviceName' => 'nullable',
                'AppModel' => 'nullable',
                'AppOsVersion' => 'nullable',
                'AppPlatform' => 'nullable',
                'AppVersion' => 'nullable',
                'IsComboAcceptAnyOdds' => 'nullable',
                'WagerSelectionInfos' => 'required',
                'ComboSelections' => 'nullable',
                'ComboSelections.*.ComboSelection' => 'nullable|integer',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            //TODO 防止無金額下注用, 未來加入驗證訊息要拿掉
            if (empty($request->input('ComboSelections'))) {
                throw new \Exception('请确实输入投注选项', 112033006);
            }

            $wagerType = $request->input('WagerType');
            $customerMacAddress = $request->input('CustomerMacAddress');
            $appDeviceName = $request->input('AppDeviceName');
            $appModel = $request->input('AppModel');
            $appOsVersion = $request->input('AppOsVersion');
            $appPlatform = $request->input('AppPlatform');
            $appVersion = $request->input('AppVersion');
            $isComboAcceptAnyOdds = $request->input('IsComboAcceptAnyOdds');
            $wagerSelectionInfos = $request->input('WagerSelectionInfos');
            $comboSelections = $request->input('ComboSelections');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->placeBet(
                $wagerType,
                $wagerSelectionInfos,
                $comboSelections,
                $token,
                $memberCode,
                $languageCode,
                $customerMacAddress,
                $appDeviceName,
                $appModel,
                $appOsVersion,
                $appPlatform,
                $appVersion,
                $isComboAcceptAnyOdds
            );

            if (!$data)
                throw new \Exception('端口回传资料为空', 112030000);

            // 注單狀態只抓內層, 內層任何一張單有狀況就會回傳錯誤代碼
            $wagerCode = $data['StatusCode'];
            foreach ($data['WagerSelectionInfos'] ?? [] as $wagerInfo) {
                if ($wagerInfo['BetStatusMessage'] != 100) {
                    $wagerCode = $wagerInfo['BetStatusMessage'];
                    break;
                }
            }
            if ($wagerCode != 100) {
                throw new \Exception(null, $wagerCode);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取投注明细
    public function getBetList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'BetConfirmationStatus' => 'required',
                'StartDate' => 'nullable',
                'EndDate' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $betConfirmationStatus = $request->input('BetConfirmationStatus');
            $startDate = $request->input('StartDate');
            $endDate = $request->input('EndDate');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getBetList($betConfirmationStatus, $languageCode, $token, $memberCode, $startDate, $endDate);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取投注帳目
    public function getStatement(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'DateType' => 'nullable',
                'StartDate' => 'required',
                'EndDate' => 'required',
                'StartTime' => 'nullable',
                'EndTime' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $dateType = $request->input('DateType');
            $startDate = $request->input('StartDate');
            $endDate = $request->input('EndDate');
            $startTime = $request->input('StartTime');
            $endTime = $request->input('EndTime');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->getStatement($token, $memberCode, $startDate, $endDate, $languageCode, $dateType, $startTime, $endTime);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 取得會員餘額
    public function getBalance(Request $request)
    {
        try {
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getBalance($token, $memberCode);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取待處理賭注狀態
    public function getPendingWagerStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'WagerIds' => 'required',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $wagerIds = $request->input('WagerIds');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->getPendingWagerStatus($token, $wagerIds, $memberCode, $languageCode);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取通告
    public function getAnnouncement(Request $request)
    {
        try {
            $imSport = new RepositoryIMSport();

            $data = $imSport->getAnnouncement();

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取用戶自定義
    public function getUserPreference(Request $request)
    {
        try {
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getUserPreference($token, $memberCode);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 更新用户自定義
    public function updateUserPreference(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SelectedOddsType' => 'required',
                'SelectedTimeZone' => 'required',
                'FastBetSingle' => 'required|array|between:3,3',
                'FastBetSingle.*' => 'required|integer',
                'FastBetCombo' => 'required|array|between:3,3',
                'FastBetCombo.*' => 'required|integer',
                'SelectedSport' => 'nullable',
                'SelectedEventSorting' => 'nullable',
                'SportId' => 'nullable',
                'SelectedViewType' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $selectedOddsType = $request->input('SelectedOddsType');
            $selectedTimeZone = $request->input('SelectedTimeZone');
            $fastBetSingle = $request->input('FastBetSingle');
            $fastBetCombo = $request->input('FastBetCombo');
            $selectedSport = $request->input('SelectedSport');
            $selectedEventSorting = $request->input('SelectedEventSorting');
            $sportId = $request->input('SportId');
            $selectedViewType = $request->input('SelectedViewType');

            $imSport = new RepositoryIMSport();

            $data = $imSport->updateUserPreference(
                $token,
                $memberCode,
                $selectedOddsType,
                $selectedTimeZone,
                $fastBetSingle,
                $fastBetCombo,
                $selectedSport,
                $selectedEventSorting,
                $sportId,
                $selectedViewType
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取收藏賽事
    public function getFavouriteEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportIds' => 'nullable',
                'BetTypeIds' => 'nullable',
                'PeriodIds' => 'nullable',
                'MarketlineLevels' => 'nullable',
                'OddsType' => 'required',
                'OrderBy' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportIds = $request->input('SportIds');
            $betTypeIds = $request->input('BetTypeIds');
            $periodIds = $request->input('PeriodIds');
            $marketLineLevels = $request->input('MarketlineLevels');
            $oddsType = $request->input('OddsType');
            $orderBy = $request->input('OrderBy');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->getFavouriteEvent(
                $oddsType,
                $memberCode,
                $token,
                $sportIds,
                $betTypeIds,
                $periodIds,
                $marketLineLevels,
                $orderBy,
                $languageCode
            );

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 新增收藏賽事
    public function addFavouriteEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'EventIds' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $eventIds = $request->input('EventIds');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->addFavouriteEvent($sportId, $eventIds, $token, $memberCode, $languageCode);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 移除收藏賽事
    public function removeFavouriteEvent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'EventIds' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $eventIds = $request->input('EventIds');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $imSport = new RepositoryIMSport();

            $data = $imSport->removeFavouriteEvent($sportId, $eventIds, $token, $memberCode, $languageCode);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取下注紀錄
    public function getBetTrade(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'EventId' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $eventId = $request->input('EventId');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $imSport = new RepositoryIMSport();

            $data = $imSport->getBetTrade($languageCode, $token, $memberCode, $eventId);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 提交回購
    public function submitBuyBack(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'WagerId' => 'required',
                'BuyBackPricing' => 'required',
                'PricingId' => 'required'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $wagerId = $request->input('WagerId');
            $buyBackPricing = $request->input('BuyBackPricing');
            $pricingId = $request->input('PricingId');
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $imSport = new RepositoryIMSport();

            $data = $imSport->submitBuyBack($wagerId, $buyBackPricing, $pricingId, $token, $memberCode);

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 索取服務器時間
    public function getServerTime(Request $request)
    {
        try {
            $imSport = new RepositoryIMSport();

            $data = $imSport->getServerTime();

            if ($data['StatusCode'] != 100) {
                throw new \Exception(null, $data['StatusCode']);
            }

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            $result = array_merge([
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ], $data ?? []);
            return Response()->json($result);
        }
    }

    // 測試用Get
    public function getTest()
    {
        return [
            'sys_result' => 'Success',
            'sys_code' => 200,
            'sys_execution' => microtime(true) - LARAVEL_START
        ];
    }

    // 檢索賽事是否存在(RD3聊天室需要)
    public function postExistEvent(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'EventIds' => 'required|array',
                'EventIds.*' => 'required|integer'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $eventIds = $request->input('EventIds');

            $data = $IMSport->postExistEvent($eventIds);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取參賽表計數
    public function getEventCount(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'SportId' => 'required',
                'IsCombo' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $sportId = $request->input('SportId');
            $isCombo = $request->input('IsCombo', false);

            $data = $IMSport->getEventCount($languageCode, $sportId, $isCombo);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取國家聯盟賽事清單
    public function getEventList(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'SportId' => 'required',
                'IsCombo' => 'nullable',
                'EventDate' => 'nullable',
                'Key' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $sportId = $request->input('SportId');
            $isCombo = $request->input('IsCombo', false);
            $eventDate = $request->input('EventDate');
            $Key = $request->input('Key'); // 取得條件 null=所有, 1=早盤, 2=今日(GMT-4) 3=取前三熱門

            $data = $IMSport->getEventList($languageCode, $sportId, $isCombo, $eventDate, $Key);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取冠軍聯盟賽事清單
    public function getOutrightEventList(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'SportId' => 'required',
                'IsCombo' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $sportId = $request->input('SportId');
            $isCombo = $request->input('IsCombo', false);

            $data = $IMSport->getOutrightEventList($languageCode, $sportId, $isCombo);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取指定聯盟下的冠軍賽事
    public function getLeagueOutrightEvent(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'CompetitionId' => 'nullable',
                'CompetitionIds' => 'nullable',
                'IsCombo' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $competitionId = $request->input('CompetitionId');
            $competitionIds = $request->input('CompetitionIds');
            $isCombo = $request->input('IsCombo', false);

            $data = $IMSport->getLeagueOutrightEvent($sportId, $languageCode, $competitionId, $competitionIds, $isCombo);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取首頁資訊(mobile)
    public function getHomeInfo(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'IsCombo' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $isCombo = $request->input('IsCombo', false);

            $data = $IMSport->getHomeInfo($languageCode, $isCombo);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取現有賽事體育清單
    public function getSportMapping(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
                'IsLive' => 'nullable',
                'IsCombo' => 'nullable',
                'Market' => 'nullable'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";
            $isCombo = $request->input('IsCombo', false);
            $isLive = $request->input('IsLive');
            $market = $request->input('Market');

            $data = $IMSport->getSportMapping($languageCode, $isCombo, $isLive, $market);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取Widget動畫賽事清單
    public function getWidgetEventList(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID',
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";

            $data = $IMSport->getWidgetEventList($languageCode);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取Widget動畫
    public function getWidgetLoader(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LanguageCode' => 'nullable|in:ENG,CHS,CHT,TH,VN',
                'EventId' => 'required|integer'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $languageCode = $request->input('LanguageCode') ?? $request->cookie('abs-lang') ?? "CHS";
            $eventId = $request->input('EventId');

            $langMapping = [    // 語系對應
                'ENG' => 'en',
                'CHS' => 'zh',
                'CHT' => 'zht',
                'TH' => 'th',
                'VN' => 'vi'
            ];

            return view('widget', ['languageCode' => $langMapping[$languageCode], 'eventId' => $eventId]);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取所有賽事計數
    public function getAllEventCount(Request $request, IMSport $IMSport)
    {
        try {
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? 'CHS';
            $token = $request->cookie('abs-token') ?? $request->input('Token');
            $memberCode = $request->cookie('abs-mem') ?? $request->input('MemberCode');

            $data = $IMSport->getAllEventCount($languageCode, $token, $memberCode);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START,
                'EventCount' => $data
            ];

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取玩法的賽事計數(PC)
    public function getBetTypeCount(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'Key' => 'required | in:1,2,3',
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $Key = $request->input('Key');

            $data = $IMSport->getBetTypeCountV2($Key);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $data;

            return Response()->json($result);
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }

    // 索取所有日期(早餐+今日)的賽事
    public function getEventInfoAllMarket(Request $request, IMSport $IMSport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'SportId' => 'required',
                'Page' => 'required',
                'OrderBy' => 'required',
                'OddsType' => 'nullable',
                'IsCombo' => 'nullable',
                'LanguageCode' => 'in:ENG,CHS,JAP,KOR,TH,VN,ID'
            ]);

            if ($validator->fails()) {
                throw new \Exception('参数输入错误', 112033000);
            }

            $sportId = $request->input('SportId');
            $page = $request->input('Page');
            $orderBy = $request->input('OrderBy');           // 指定使用的排序
            $oddsType = $request->input('OddsType') ?? 3;    // 預設盤口為歐洲盤
            $isCombo = $request->input('IsCombo') ?? true;   // 預設為連串過關
            $languageCode = $request->cookie('abs-lang') ?? $request->input('LanguageCode') ?? "CHS";   // 預設為簡中

            $response = $IMSport->getEventInfoAllMarket($sportId, $page, $orderBy, $oddsType, $isCombo, $languageCode);

            $result = [
                'sys_result' => 'Success',
                'sys_code' => 200,
                'sys_custom' => '',
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
            $result += $response;
            return $result;
        } catch (\Exception $error) {
            $catchEvent = event(new CatchException($request, $error->getCode(), $error->getMessage()));

            return [
                'sys_result' => $catchEvent[0]['result'],
                'sys_code' => $catchEvent[0]['code'],
                'sys_custom' => $catchEvent[0]['customMsg'],
                'sys_execution' => microtime(true) - LARAVEL_START
            ];
        }
    }
}
