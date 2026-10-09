
/****** Object:  Table [dbo].[WEBENGINE_REGISTER_ACCOUNT]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_REGISTER_ACCOUNT](
	[registration_account] [varchar](50) NOT NULL,
	[registration_password] [varchar](50) NOT NULL,
	[registration_email] [varchar](50) NOT NULL,
	[registration_date] [varchar](50) NOT NULL,
	[registration_ip] [varchar](50) NOT NULL,
	[registration_key] [varchar](50) NOT NULL,
 CONSTRAINT [PK_WEBENGINE_REGISTER_ACCOUNT] PRIMARY KEY CLUSTERED 
(
	[registration_account] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

