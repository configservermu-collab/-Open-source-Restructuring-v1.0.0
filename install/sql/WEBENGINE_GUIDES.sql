
/****** Object:  Table [dbo].[WEBENGINE_GUIDES]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_GUIDES](
	[guides_id] [int] IDENTITY(1,1) NOT NULL,
	[guides_title] [varchar](max) NOT NULL,
	[guides_author] [varchar](50) NOT NULL,
	[guides_date] [varchar](50) NOT NULL,
	[guides_content] [text] NOT NULL,
	[allow_comments] [int] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF


