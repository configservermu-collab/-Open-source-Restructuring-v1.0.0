
/****** Object:  Table [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM_CONTENT]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM_CONTENT](
	[reply_id] [int] IDENTITY(1,1) NOT NULL,
	[ticket_id] [varchar](50) NOT NULL,
	[reply_author] [varchar](50) NOT NULL,
	[reply_date] [varchar](50) NOT NULL,
	[reply_content] [text] NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF

