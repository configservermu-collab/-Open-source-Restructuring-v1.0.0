
/****** Object:  Table [dbo].[WEBENGINE_DOWNLOADS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_DOWNLOADS](
	[download_id] [int] IDENTITY(1,1) NOT NULL,
	[download_title] [varchar](100) NOT NULL,
	[download_description] [varchar](100) NULL,
	[download_link] [varchar](max) NOT NULL,
	[download_size] [float] NULL,
	[download_type] [int] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_DOWNLOADS] ON 

INSERT [dbo].[WEBENGINE_DOWNLOADS] ([download_id], [download_title], [download_description], [download_link], [download_size], [download_type]) VALUES (2, N'Continent MU', N'Mediafire', N'Cuál sería el cliente disculpa ? https://www.mediafire.com/file/xpsbap81rycrf7q/Continent+MU+S6.rar/file', 960, 1)
INSERT [dbo].[WEBENGINE_DOWNLOADS] ([download_id], [download_title], [download_description], [download_link], [download_size], [download_type]) VALUES (1, N'Cliente Villa', N'Mediafire', N'https://www.mediafire.com/file/017hqxacyylt34y/Mu+Villa+S6.rar/file', 650, 1)
INSERT [dbo].[WEBENGINE_DOWNLOADS] ([download_id], [download_title], [download_description], [download_link], [download_size], [download_type]) VALUES (1, N'Cliente Villa', N'Mediafire', N'https://www.mediafire.com/file/017hqxacyylt34y/Mu+Villa+S6.rar/file', 650, 1)
SET IDENTITY_INSERT [dbo].[WEBENGINE_DOWNLOADS] OFF


