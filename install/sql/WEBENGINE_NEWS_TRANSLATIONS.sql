
/****** Object:  Table [dbo].[WEBENGINE_NEWS_TRANSLATIONS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_NEWS_TRANSLATIONS](
	[news_id] [int] NOT NULL,
	[news_language] [varchar](10) NOT NULL,
	[news_title] [varchar](max) NOT NULL,
	[news_content] [text] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF


