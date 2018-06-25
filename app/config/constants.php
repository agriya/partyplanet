<?php
/**
 * Party Planet
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    partyplanet
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class ConstUserTypes
{
    const Admin = 1;
    const User = 2;
    //    const Promoter = 3;
    const VenueOwner = 4;
}
class ConstVideoViewType
{
    const NormalView = 1;
    const EmbedView = 2;
    const EmbedAutoPlayView = 3;
}
class ConstViewType
{
    const NormalView = 1;
    const FullView = 2;
    const EmbedView = 3;
    const EmbedFullView = 4;
}
class ConstAttachment
{
    const UserAvatar = 1;
    const Event = 3;
    const Venue = 4;
    const EventSponsor = 8;
    const PhotoAlbum = 7;
    const Photo = 5;
    const Article = 6;
    const VenueSponsor = 9;
    const Video = 2;
}
class ConstUploadedVia
{
    const File = 1;
    const Record = 2;
    const Embed = 3;
}
class ConstFriendRequestStatus
{
    const Pending = 1;
    const Approved = 2;
    const Reject = 3;
}
class ConstMessageFolder
{
    const Inbox = 1;
    const SentMail = 2;
    const Drafts = 3;
    const Spam = 4;
    const Trash = 5;
}
class ConstUserFriendStatus
{
    const Pending = 1;
    const Approved = 2;
    const Rejected = 3;
}
// setting for one way and two way friendships
class ConstUserFriendType
{
    const IsTwoWay = true;
}
// Setting for privacy settings
class ConstPrivacySetting
{
    const EveryOne = 1;
    const Users = 2;
    const Friends = 3;
    const Nobody = 4;
}
class ConstMoreAction
{
    const Inactive = 1;
    const Active = 2;
    const Delete = 3;
    const OpenID = 4;
    const Featured = 5;
    const NonFeatured = 6;
    const Export = 7;
    const Approved = 8;
    const Disapproved = 9;
    const Suspend = 10;
    const Twitter = 11;
    const Facebook = 12;
    const Flagged = 13;
    const Unflagged = 14;
    const Unsuspend = 15;
    const Normal = 38;
    const Gmail = 39;
    const Yahoo = 40;
    const Checked = 28;
    const Unchecked = 29;
    const Site = 30;
    const Cancel = 16;
    const Hotties = 17;
    const Contacted = 18;
    const NotContacted = 19;
}
class ConstFollowingType
{
    const IamFollowing = 1;
    const FollowingMe = 2;
}
// Banned ips types
class ConstBannedTypes
{
    const SingleIPOrHostName = 1;
    const IPRange = 2;
    const RefererBlock = 3;
}
// Banned ips durations
class ConstBannedDurations
{
    const Permanent = 1;
    const Days = 2;
    const Weeks = 3;
}
class ConstInvitedFriendStatus
{
    const MyFriend = 1;
    const FriendOnUboo = 2;
    const NewFriend = 3;
}
class ConstUserFilter
{
    const EmailAddress = 1;
    const UserName = 2;
    const FirstName = 3;
    const LastName = 4;
    const FirstAndLastName = 5;
}
class ConstUserSearchFilter
{
    const AllAreas = 1;
    const ZipCode = 2;
    const City = 3;
}
class ConstNos
{
    const First = 1;
    const Second = 2;
    const Third = 3;
    const Four = 3;
}
class ConstBeerPrice
{
    const First = '$3 or less';
    const Second = '$4 - $6';
    const Third = '$7 - $9';
    const Four = '$10 or more';
}
class ConstFoodSold
{
    const First = 'none';
    const Second = 'bar food';
    const Third = 'sit-down entrees';
}
class ConstEmployeeSize
{
    const First = '< 200';
    const Second = '200 - 1000';
    const Third = '1000 - 10000';
    const Four = '10000 or more';
}
class ConstSquareFootage
{
    const First = '< 500';
    const Second = '500 - 1000';
    const Third = '1000 - 10000';
    const Four = '10000 or more';
}
class ConstSalesVolume
{
    const First = '< 300';
    const Second = '300 - 1000';
    const Third = '1000 - 10000';
    const Four = '10000 or more';
}
class ConstLiveBand
{
    const First = 'none';
    const Second = 'very rarely';
    const Third = 'weekly';
    const Four = 'almost daily';
}
class ConstURLFilter
{
    const Commented = 'commented';
    const Flagged = 'flagged';
    const Viewed = 'viewed';
    const Favorited = 'favorited';
    const Rated = 'rated';
    const Downloaded = 'downloaded';
}
class ConstPaymentTypes
{
    const MoneyBooker = 1;
    const PayPal = 2;
}
class ConstPaymentGateways
{
    const MoneyBooker = 1;
    const PayPal = 2;
	const AdaptivePayPal = 3;
}
class ConstTransactionTypes
{
    const BoughtEnhancementPackageInVenue = 1;
    const BoughtEnhancementPackageInEvent = 2;
    const TicketBooking = 3;
}
/* affiliate class constatn */
class ConstAffiliateCashWithdrawalStatus
{
    const Pending = 1;
    const Approved = 2;
    const Rejected = 3;
    const Failed = 4;
    const Success = 5;
}
class ConstCommsisionType
{
    const Amount = 'amount';
    const Percentage = 'percentage';
}
class ConstAffiliateStatus
{
    const Pending = 1;
    const Canceled = 2;
    const PipeLine = 3;
    const Completed = 4;
}
class ConstAffiliateCommissionType
{
    const Percentage = 1;
    const Amount = 2;
}
class ConstAffiliateRequests
{
    const Pending = 0;
    const Accepted = 1;
    const Rejected = 2;
}
class ConstFileExt
{
    const Flv = 'flv';
    const Jpeg = 'jpeg';
    const Gif = 'gif';
    const Bmp = 'bmp';
    const Png = 'png';
}
class ConstModule
{
    const Affiliate = 14;
    const Friends = 12;
}
class ConstModuleEnableFields
{
    const Affiliate = 160;
    const Friends = 253;
}
class ConstPaymentGatewayFlow
{
	const BuyerSiteSeller = 'Attendee -> Site -> Event Owner';
	const BuyerSellerSite = 'Attendee -> Event Owner -> Site';
}
class ConstPaymentGatewayFee
{	
	const Seller = 'Event Owner';
	const Site = 'Site';
	const SiteAndSeller = 'Site and Event Owner';
}
class ConstRsvpResponse
{	
	const Yes = 1;
	const No = 2;
	const Maybe = 3;
}
?>