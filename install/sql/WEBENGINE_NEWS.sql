
/****** Object:  Table [dbo].[WEBENGINE_NEWS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_NEWS](
	[news_id] [int] IDENTITY(1,1) NOT NULL,
	[news_title] [varchar](max) NOT NULL,
	[news_author] [varchar](50) NOT NULL,
	[news_date] [varchar](50) NOT NULL,
	[news_content] [text] NOT NULL,
	[allow_comments] [int] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_NEWS] ON 

INSERT [dbo].[WEBENGINE_NEWS] ([news_id], [news_title], [news_author], [news_date], [news_content], [allow_comments]) VALUES (5, N'dGVzdA==', N'Administrator', N'1753821154', N'PHA+dGVzdDwvcD4=', 0)
SET IDENTITY_INSERT [dbo].[WEBENGINE_NEWS] OFF


