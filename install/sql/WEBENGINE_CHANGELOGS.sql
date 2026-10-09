
/****** Object:  Table [dbo].[WEBENGINE_CHANGELOGS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_CHANGELOGS](
	[changelogs_id] [int] IDENTITY(1,1) NOT NULL,
	[changelogs_title] [varchar](max) NOT NULL,
	[changelogs_author] [varchar](50) NOT NULL,
	[changelogs_date] [varchar](50) NOT NULL,
	[changelogs_content] [text] NOT NULL,
	[changelogs_prefijo] [int] NOT NULL,
	[allow_comments] [int] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF


