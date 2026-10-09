
/****** Object:  Table [dbo].[WEBENGINE_BANS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_BANS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[account_id] [varchar](50) NOT NULL,
	[banned_by] [varchar](50) NOT NULL,
	[ban_date] [int] NOT NULL,
	[ban_days] [int] NOT NULL,
	[ban_reason] [varchar](100) NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF


