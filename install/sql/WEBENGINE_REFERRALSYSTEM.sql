
/****** Object:  Table [dbo].[WEBENGINE_REFERRALSYSTEM]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_REFERRALSYSTEM](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[referral_username] [varchar](10) NOT NULL,
	[referral_character] [varchar](10) NOT NULL,
	[referred_username] [varchar](10) NOT NULL,
	[referred_character] [varchar](10) NULL,
	[referred_registration_date] [datetime] NOT NULL,
	[referred_last_check] [datetime] NULL,
	[referred_complete_date] [datetime] NULL,
	[status] [tinyint] NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF

ALTER TABLE [dbo].[WEBENGINE_REFERRALSYSTEM] ADD  CONSTRAINT [DF_WEBENGINE_REFERRALSYSTEM_status]  DEFAULT ((0)) FOR [status]

