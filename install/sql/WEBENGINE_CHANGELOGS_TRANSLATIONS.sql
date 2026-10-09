
/****** Object:  Table [dbo].[WEBENGINE_CHANGELOGS_TRANSLATIONS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_CHANGELOGS_TRANSLATIONS](
	[changelogs_id] [int] NOT NULL,
	[changelogs_language] [varchar](10) NOT NULL,
	[changelogs_title] [varchar](max) NOT NULL,
	[changelogs_content] [text] NOT NULL,
	[changelogs_prefijo] [varchar](max) NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF


