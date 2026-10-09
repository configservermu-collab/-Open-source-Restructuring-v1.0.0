
/****** Object:  Table [dbo].[WEBENGINE_GUIDES_TRANSLATIONS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_GUIDES_TRANSLATIONS](
	[guides_id] [int] NOT NULL,
	[guides_language] [varchar](10) NOT NULL,
	[guides_title] [varchar](max) NOT NULL,
	[guides_content] [text] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF


