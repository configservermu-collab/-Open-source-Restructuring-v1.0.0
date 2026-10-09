
/****** Object:  Table [dbo].[WEBENGINE_VOTES]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_VOTES](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[user_id] [int] NOT NULL,
	[user_ip] [varchar](50) NOT NULL,
	[vote_site_id] [int] NOT NULL,
	[timestamp] [varchar](50) NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF

